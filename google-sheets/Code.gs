/**
 * Synchronisation Google Sheet → site AS amU.
 *
 * Ce script est lié au Google Sheet de budget existant. Il lit uniquement
 * l’onglet NATIONAL : SPORT, CHAMPIONNATS, NOMS, PRENOMS, CATEGORIE et
 * RÉSULTATS / NIVEAU. Les colonnes de budget, transport ou inscription ne
 * sont jamais envoyées au site.
 *
 * À configurer une seule fois dans les propriétés du script :
 * - SITE_SYNC_URL : URL affichée dans Administration AS amU > Google Sheets
 * - SHARED_SECRET : le même code secret affiché dans l’administration
 * - DEFAULT_SEASON : par exemple 2025/2026
 */

const NATIONAL_SHEET_NAME = 'NATIONAL';

function onOpen() {
  SpreadsheetApp.getUi()
    .createMenu('AS amU')
    .addItem('Synchroniser les résultats avec le site', 'syncNow')
    .addItem('Activer la synchronisation automatique', 'installAutomaticSync')
    .addToUi();
}

/** Lance un envoi immédiat pour vérifier la configuration. */
function syncNow() {
  const result = syncNationalResults_();
  SpreadsheetApp.getUi().alert(
    result.entries + ' podium(s) envoyé(s) au site AS amU.'
  );
}

/**
 * À lancer une seule fois depuis Apps Script.
 * Il crée un déclencheur autorisé à envoyer les résultats au site dès qu’une
 * cellule de l’onglet NATIONAL est modifiée.
 */
function installAutomaticSync() {
  const spreadsheet = SpreadsheetApp.getActive();
  ScriptApp.getProjectTriggers()
    .filter(trigger => trigger.getHandlerFunction() === 'onNationalResultsEdit_')
    .forEach(trigger => ScriptApp.deleteTrigger(trigger));

  ScriptApp.newTrigger('onNationalResultsEdit_')
    .forSpreadsheet(spreadsheet)
    .onEdit()
    .create();

  SpreadsheetApp.getUi().alert(
    'Synchronisation automatique activée pour l’onglet NATIONAL.'
  );
}

/** Déclencheur installable : ne fait rien pour les autres onglets. */
function onNationalResultsEdit_(event) {
  try {
    if (!event || !event.range || event.range.getSheet().getName() !== NATIONAL_SHEET_NAME) {
      return;
    }
    syncNationalResults_();
  } catch (error) {
    console.error('AS amU sync error: ' + String(error.message || error));
  }
}

function syncNationalResults_() {
  const properties = PropertiesService.getScriptProperties();
  const endpoint = String(properties.getProperty('SITE_SYNC_URL') || '').trim();
  const secret = String(properties.getProperty('SHARED_SECRET') || '').trim();
  const season = String(properties.getProperty('DEFAULT_SEASON') || '').trim();
  if (!endpoint || !secret || !season) {
    throw new Error('Ajoute SITE_SYNC_URL, SHARED_SECRET et DEFAULT_SEASON dans les propriétés du script.');
  }

  const entries = nationalPodiums_();
  const response = UrlFetchApp.fetch(endpoint, {
    method: 'post',
    contentType: 'application/json',
    payload: JSON.stringify({
      secret: secret,
      source: 'Google Sheet · NATIONAL',
      entries: entries.map(entry => Object.assign({ season: season }, entry)),
    }),
    muteHttpExceptions: true,
  });
  const status = response.getResponseCode();
  const body = response.getContentText();
  if (status < 200 || status >= 300) {
    throw new Error('Le site a répondu ' + status + ' : ' + body);
  }

  const answer = JSON.parse(body || '{}');
  if (!answer.ok) {
    throw new Error(answer.error || 'Le site n’a pas confirmé la synchronisation.');
  }
  return { entries: entries.length };
}

/**
 * Convertit uniquement les podiums 1, 2 et 3 de la feuille NATIONAL.
 * Les cellules SPORT et CHAMPIONNATS sont propagées aux lignes suivantes,
 * exactement comme dans le tableau de budget actuel.
 */
function nationalPodiums_() {
  const sheet = SpreadsheetApp.getActive().getSheetByName(NATIONAL_SHEET_NAME);
  if (!sheet) {
    throw new Error('La feuille « NATIONAL » est introuvable.');
  }
  const values = sheet.getDataRange().getDisplayValues();
  const header = findNationalHeader_(values);
  if (!header) {
    throw new Error('Les colonnes SPORT, CHAMPIONNATS, NOMS, PRENOMS et RÉSULTATS / NIVEAU sont introuvables.');
  }

  const entries = [];
  const teamKeys = new Set();
  let sport = '';
  let competition = '';
  let category = '';

  for (let rowIndex = header.row + 1; rowIndex < values.length; rowIndex++) {
    const row = values[rowIndex];
    if (cell_(row, header.columns.sport)) {
      sport = cell_(row, header.columns.sport);
      competition = '';
      category = '';
    }
    if (cell_(row, header.columns.competition)) competition = cell_(row, header.columns.competition);
    if (header.columns.category !== undefined && cell_(row, header.columns.category)) {
      category = cell_(row, header.columns.category);
    }

    const lastName = cell_(row, header.columns.lastName);
    const firstName = cell_(row, header.columns.firstName);
    const result = cell_(row, header.columns.result);
    const place = podiumPlace_(result);
    if (!place || !sport || (!lastName && !firstName && !result)) {
      continue;
    }

    const entry = { sport: sport, competition: competition, result: result, place: place };
    if (isTeamSport_(sport, category)) {
      const key = [compact_(sport), compact_(competition), compact_(category), compact_(result), place].join('|');
      if (!teamKeys.has(key)) {
        teamKeys.add(key);
        entry.team = true;
        entries.push(entry);
      }
    } else if (lastName || firstName) {
      entry.last_name = lastName;
      entry.first_name = firstName;
      entries.push(entry);
    }
  }
  return entries;
}

function findNationalHeader_(values) {
  for (let rowIndex = 0; rowIndex < Math.min(values.length, 12); rowIndex++) {
    const normalized = values[rowIndex].map(compact_);
    const columns = {
      sport: findColumn_(normalized, ['SPORT']),
      competition: findColumn_(normalized, ['CHAMPIONNATS', 'CHAMPIONNAT', 'COMPETITION']),
      lastName: findColumn_(normalized, ['NOMS', 'NOM']),
      firstName: findColumn_(normalized, ['PRENOMS', 'PRENOM']),
      result: findColumn_(normalized, ['RESULTATSNIVEAU', 'RESULTATNIVEAU', 'RESULTATS', 'RESULTAT']),
      category: findColumn_(normalized, ['CATEGORIE', 'CATEGORIES']),
    };
    if ([columns.sport, columns.competition, columns.lastName, columns.firstName, columns.result]
      .every(column => column !== undefined)) {
      return { row: rowIndex, columns: columns };
    }
  }
  return null;
}

function findColumn_(headers, accepted) {
  const index = headers.findIndex(value => accepted.indexOf(value) !== -1);
  return index === -1 ? undefined : index;
}

function cell_(row, index) {
  return index === undefined ? '' : String(row[index] || '').trim();
}

function podiumPlace_(result) {
  const value = fold_(result).toUpperCase();
  if (/(^|[^A-Z])VICE[ -]?CHAMPION/.test(value)) return 2;
  if (/(^|[^A-Z])CHAMPION(?:NE|S|NES)?(?=$|[^A-Z])/.test(value)) return 1;
  if (/(^|[^0-9A-Z])(?:1ER|1ERE|1RE|1ST|OR|GOLD)(?=$|[^0-9A-Z])/.test(value)) return 1;
  if (/(^|[^0-9A-Z])(?:2E|2EME|2ND|ARGENT|SILVER)(?=$|[^0-9A-Z])/.test(value)) return 2;
  if (/(^|[^0-9A-Z])(?:3E|3EME|3RD|BRONZE)(?=$|[^0-9A-Z])/.test(value)) return 3;
  return null;
}

function isTeamSport_(sport, category) {
  const value = compact_(sport + ' ' + category);
  if (value.indexOf('EQUIPE') !== -1 || value.indexOf('COLLECTIF') !== -1) return true;
  return ['FOOTBALL', 'FUTSAL', 'BASKET', 'HANDBALL', 'VOLLEY', 'RUGBY', 'WATERPOLO', 'HOCKEY', 'ULTIMATE']
    .some(teamSport => value.indexOf(teamSport) !== -1);
}

function fold_(value) {
  return String(value || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

function compact_(value) {
  return fold_(value).toUpperCase().replace(/[^A-Z0-9]+/g, '');
}
