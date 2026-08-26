const navToggle = document.querySelector('.nav-toggle');
const navLinks = document.querySelector('.nav-links');

if (navToggle && navLinks) {
  navToggle.addEventListener('click', () => {
    const isOpen = navLinks.classList.toggle('open');
    navToggle.setAttribute('aria-expanded', String(isOpen));
  });
}

document.querySelectorAll('.has-dropdown').forEach((item) => {
  const button = item.querySelector('.dropdown-toggle');

  if (!button) return;

  button.addEventListener('click', (event) => {
    event.stopPropagation();
    const willOpen = !item.classList.contains('open');

    document.querySelectorAll('.has-dropdown.open').forEach((openItem) => {
      if (openItem !== item) {
        openItem.classList.remove('open');
        openItem.querySelector('.dropdown-toggle')?.setAttribute('aria-expanded', 'false');
      }
    });

    item.classList.toggle('open', willOpen);
    button.setAttribute('aria-expanded', String(willOpen));
  });
});

document.addEventListener('click', (event) => {
  if (event.target.closest('.has-dropdown')) return;

  document.querySelectorAll('.has-dropdown.open').forEach((item) => {
    item.classList.remove('open');
    item.querySelector('.dropdown-toggle')?.setAttribute('aria-expanded', 'false');
  });
});

document.addEventListener('keydown', (event) => {
  if (event.key !== 'Escape') return;

  document.querySelectorAll('.has-dropdown.open').forEach((item) => {
    item.classList.remove('open');
    item.querySelector('.dropdown-toggle')?.setAttribute('aria-expanded', 'false');
  });

  if (navLinks?.classList.contains('open')) {
    navLinks.classList.remove('open');
    navToggle?.setAttribute('aria-expanded', 'false');
  }
});

function setupFilters({ buttonSelector, itemSelector, filterKey, itemKey, emptySelector }) {
  const buttons = [...document.querySelectorAll(buttonSelector)];
  const items = [...document.querySelectorAll(itemSelector)];
  const emptyState = document.querySelector(emptySelector);

  buttons.forEach((button) => {
    button.addEventListener('click', () => {
      const filter = button.dataset[filterKey];
      let visibleItems = 0;

      buttons.forEach((currentButton) => {
        const isActive = currentButton === button;
        currentButton.classList.toggle('active', isActive);
        currentButton.setAttribute('aria-pressed', String(isActive));
      });

      items.forEach((item) => {
        const shouldShow = filter === 'all' || item.dataset[itemKey] === filter;
        item.hidden = !shouldShow;
        item.setAttribute('aria-hidden', String(!shouldShow));
        if (shouldShow) visibleItems += 1;
      });

      if (emptyState) emptyState.hidden = visibleItems > 0;
    });
  });
}

setupFilters({
  buttonSelector: '[data-filter]',
  itemSelector: '[data-campus]',
  filterKey: 'filter',
  itemKey: 'campus',
  emptySelector: '#section-filter-empty',
});

setupFilters({
  buttonSelector: '[data-calendar-filter]',
  itemSelector: '[data-calendar-level]',
  filterKey: 'calendarFilter',
  itemKey: 'calendarLevel',
  emptySelector: '#calendar-filter-empty',
});

document.querySelectorAll('[data-compass-toggle]').forEach((button) => {
  const panelId = button.getAttribute('aria-controls');
  const panel = panelId ? document.getElementById(panelId) : null;
  const label = button.querySelector('.boussole-toggle-label');

  if (!panel) return;

  button.addEventListener('click', () => {
    const isOpen = button.getAttribute('aria-expanded') !== 'true';
    button.setAttribute('aria-expanded', String(isOpen));
    panel.hidden = !isOpen;
    panel.setAttribute('aria-hidden', String(!isOpen));

    if (label) {
      label.textContent = isOpen ? 'Masquer les options' : 'Voir les options';
    }
  });
});

const campusMap = document.querySelector('[data-campus-map]');

if (campusMap) {
  const mapFrame = campusMap.querySelector('[data-campus-map-frame]');
  const mapConsent = campusMap.querySelector('[data-campus-map-consent]');
  const mapLoadButton = campusMap.querySelector('[data-campus-map-load]');
  const mapTitle = campusMap.querySelector('[data-campus-map-title]');
  const mapCampus = campusMap.querySelector('[data-campus-map-campus]');
  const mapRoute = campusMap.querySelector('[data-campus-map-route]');
  const places = [...campusMap.querySelectorAll('[data-campus-map-place]')];
  const filters = [...campusMap.querySelectorAll('[data-campus-map-filter]')];
  const focusButtons = [...document.querySelectorAll('[data-campus-map-focus]')];
  let mapIsLoaded = false;

  const selectPlace = (source) => {
    if (!source || !mapFrame) return;

    const { mapEmbed, mapRoute: route, mapTitle: title, mapCampus: campus } = source.dataset;
    if (mapEmbed) {
      mapFrame.dataset.src = mapEmbed;
      if (mapIsLoaded) mapFrame.src = mapEmbed;
    }
    if (title) {
      mapTitle.textContent = title;
      mapFrame.title = `Carte GPS de ${title}`;
    }
    if (campus) mapCampus.textContent = campus;
    if (route) mapRoute.href = route;

    places.forEach((place) => {
      const isActive = place.dataset.mapTitle === title;
      place.classList.toggle('is-active', isActive);
      place.setAttribute('aria-pressed', String(isActive));
    });
  };

  mapLoadButton?.addEventListener('click', () => {
    if (!mapFrame?.dataset.src) return;

    mapIsLoaded = true;
    mapFrame.src = mapFrame.dataset.src;
    mapFrame.hidden = false;
    if (mapConsent) mapConsent.hidden = true;
  });

  places.forEach((place) => place.addEventListener('click', () => selectPlace(place)));

  focusButtons.forEach((button) => {
    button.addEventListener('click', () => {
      selectPlace(button);
      campusMap.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  filters.forEach((filter) => {
    filter.addEventListener('click', () => {
      const campus = filter.dataset.campusMapFilter;
      let firstVisiblePlace = null;

      filters.forEach((currentFilter) => {
        const isActive = currentFilter === filter;
        currentFilter.classList.toggle('is-active', isActive);
        currentFilter.setAttribute('aria-pressed', String(isActive));
      });

      places.forEach((place) => {
        const isVisible = campus === 'all' || place.dataset.campus === campus;
        place.hidden = !isVisible;
        if (isVisible && !firstVisiblePlace) firstVisiblePlace = place;
      });

      selectPlace(firstVisiblePlace);
    });
  });
}

const competitionCalendar = document.querySelector('[data-competition-calendar]');

if (competitionCalendar) {
  const eventDataScript = competitionCalendar.querySelector('[data-calendar-events]');
  const calendarGrid = competitionCalendar.querySelector('[data-calendar-grid]');
  const monthSelect = competitionCalendar.querySelector('[data-calendar-month-select]');
  const previousButton = competitionCalendar.querySelector('[data-calendar-previous]');
  const nextButton = competitionCalendar.querySelector('[data-calendar-next]');
  const todayButton = competitionCalendar.querySelector('[data-calendar-today]');
  const resetButton = competitionCalendar.querySelector('[data-calendar-reset]');
  const sportFilter = competitionCalendar.querySelector('[data-calendar-sport]');
  const levelFilter = competitionCalendar.querySelector('[data-calendar-level]');
  const statusFilter = competitionCalendar.querySelector('[data-calendar-status]');
  const monthTitle = competitionCalendar.querySelector('[data-calendar-month-title]');
  const agendaTitle = competitionCalendar.querySelector('[data-calendar-agenda-title]');
  const agenda = competitionCalendar.querySelector('[data-calendar-agenda]');
  const resultCount = competitionCalendar.querySelector('[data-calendar-result-count]');
  const eventCards = [...document.querySelectorAll('[data-calendar-event]')];
  const filterEmpty = document.querySelector('[data-calendar-filter-empty]');

  let events = [];
  try {
    events = JSON.parse(eventDataScript?.textContent || '[]');
  } catch (error) {
    events = [];
  }

  const parseDate = (value) => {
    const [year, month, day] = String(value || '').split('-').map(Number);
    return year && month && day ? new Date(year, month - 1, day, 12) : null;
  };

  const toIsoDate = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  };

  const monthKeyForDate = (date) => `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
  const monthLabel = (date) => {
    const label = new Intl.DateTimeFormat('fr-FR', { month: 'long', year: 'numeric' }).format(date);
    return label.charAt(0).toUpperCase() + label.slice(1);
  };
  const eventDateLabel = (event) => {
    const start = parseDate(event.start_date);
    const end = parseDate(event.end_date || event.start_date);
    if (!start) return '';
    const format = new Intl.DateTimeFormat('fr-FR', { day: 'numeric', month: 'short' });
    return end && toIsoDate(end) !== toIsoDate(start) ? `${format.format(start)} – ${format.format(end)}` : format.format(start);
  };
  const eventId = (event) => String(event.id || '');
  const currentMonthDate = () => parseDate(`${monthSelect.value}-01`);

  const inSeason = (key) => key >= competitionCalendar.dataset.seasonStart && key <= competitionCalendar.dataset.seasonEnd;
  const findInitialMonth = () => {
    const firstEvent = events.map((event) => parseDate(event.start_date)).find(Boolean);
    if (firstEvent && inSeason(monthKeyForDate(firstEvent))) return monthKeyForDate(firstEvent);

    const nowKey = monthKeyForDate(new Date());
    return inSeason(nowKey) ? nowKey : competitionCalendar.dataset.seasonStart;
  };

  const matchesFilters = (event) => (
    (sportFilter?.value === 'all' || event.sport === sportFilter?.value)
    && (levelFilter?.value === 'all' || event.level === levelFilter?.value)
    && (statusFilter?.value === 'all' || event.status === statusFilter?.value)
  );

  const eventIsInMonth = (event, monthDate) => {
    const start = parseDate(event.start_date);
    const end = parseDate(event.end_date || event.start_date);
    const monthStart = new Date(monthDate.getFullYear(), monthDate.getMonth(), 1, 12);
    const monthEnd = new Date(monthDate.getFullYear(), monthDate.getMonth() + 1, 0, 12);
    return Boolean(start && end && start <= monthEnd && end >= monthStart);
  };

  const openEvent = (event) => {
    const card = document.getElementById(`event-${eventId(event)}`);
    if (!card) return;
    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
    card.focus({ preventScroll: true });
  };

  const renderAgenda = (visibleEvents, monthDate) => {
    if (!agenda) return;
    const inMonth = visibleEvents.filter((event) => eventIsInMonth(event, monthDate));
    agenda.replaceChildren();

    if (!inMonth.length) {
      const empty = document.createElement('p');
      empty.className = 'calendar-agenda-empty';
      empty.textContent = 'Aucun rendez-vous ne correspond aux filtres pour ce mois.';
      agenda.append(empty);
      return;
    }

    inMonth.forEach((event) => {
      const button = document.createElement('button');
      const date = document.createElement('time');
      const title = document.createElement('strong');
      const detail = document.createElement('span');
      button.type = 'button';
      button.className = 'calendar-agenda-item';
      date.textContent = eventDateLabel(event);
      title.textContent = event.title || 'Compétition AS amU';
      detail.textContent = [event.sport, event.place].filter(Boolean).join(' · ');
      button.append(date, title, detail);
      button.addEventListener('click', () => openEvent(event));
      agenda.append(button);
    });
  };

  const renderGrid = (visibleEvents, monthDate) => {
    if (!calendarGrid) return;
    calendarGrid.replaceChildren();

    const firstDay = new Date(monthDate.getFullYear(), monthDate.getMonth(), 1, 12);
    const firstWeekday = (firstDay.getDay() + 6) % 7;
    const daysInMonth = new Date(monthDate.getFullYear(), monthDate.getMonth() + 1, 0).getDate();
    const cells = Math.ceil((firstWeekday + daysInMonth) / 7) * 7;
    const today = toIsoDate(new Date());

    for (let position = 0; position < cells; position += 1) {
      const dayDate = new Date(monthDate.getFullYear(), monthDate.getMonth(), position - firstWeekday + 1, 12);
      const isoDate = toIsoDate(dayDate);
      const isOutside = dayDate.getMonth() !== monthDate.getMonth();
      const day = document.createElement('div');
      const number = document.createElement('span');
      const dayEvents = document.createElement('div');
      day.className = `calendar-day${isOutside ? ' is-outside' : ''}${isoDate === today ? ' is-today' : ''}`;
      number.className = 'calendar-day-number';
      number.textContent = String(dayDate.getDate());
      dayEvents.className = 'calendar-day-events';
      day.append(number, dayEvents);

      if (!isOutside) {
        visibleEvents
          .filter((event) => event.start_date === isoDate)
          .forEach((event) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'calendar-day-event';
            button.dataset.level = event.level || '';
            button.textContent = event.title || 'Compétition AS amU';
            button.title = event.title || 'Compétition AS amU';
            button.addEventListener('click', () => openEvent(event));
            dayEvents.append(button);
          });
      }

      calendarGrid.append(day);
    }
  };

  const updateNavigation = () => {
    if (previousButton) previousButton.disabled = monthSelect.selectedIndex === 0;
    if (nextButton) nextButton.disabled = monthSelect.selectedIndex === monthSelect.options.length - 1;
  };

  const renderCalendar = () => {
    const monthDate = currentMonthDate();
    if (!monthDate) return;
    const visibleEvents = events.filter(matchesFilters);
    const visibleCardCount = eventCards.reduce((count, card) => {
      const shouldShow = (
        (sportFilter?.value === 'all' || card.dataset.calendarSportValue === sportFilter?.value)
        && (levelFilter?.value === 'all' || card.dataset.calendarLevelValue === levelFilter?.value)
        && (statusFilter?.value === 'all' || card.dataset.calendarStatusValue === statusFilter?.value)
      );
      card.hidden = !shouldShow;
      return count + Number(shouldShow);
    }, 0);

    if (monthTitle) monthTitle.textContent = monthLabel(monthDate);
    if (agendaTitle) agendaTitle.textContent = monthLabel(monthDate);
    if (resultCount) resultCount.textContent = `${visibleEvents.length} rendez-vous affiché${visibleEvents.length > 1 ? 's' : ''} sur la saison ${competitionCalendar.dataset.seasonStart.slice(0, 4)}/${Number(competitionCalendar.dataset.seasonStart.slice(0, 4)) + 1}.`;
    if (filterEmpty) filterEmpty.hidden = visibleCardCount > 0;

    renderGrid(visibleEvents, monthDate);
    renderAgenda(visibleEvents, monthDate);
    updateNavigation();
  };

  monthSelect.value = findInitialMonth();
  [sportFilter, levelFilter, statusFilter].filter(Boolean).forEach((filter) => filter.addEventListener('change', renderCalendar));
  monthSelect.addEventListener('change', renderCalendar);
  previousButton?.addEventListener('click', () => {
    monthSelect.selectedIndex = Math.max(0, monthSelect.selectedIndex - 1);
    renderCalendar();
  });
  nextButton?.addEventListener('click', () => {
    monthSelect.selectedIndex = Math.min(monthSelect.options.length - 1, monthSelect.selectedIndex + 1);
    renderCalendar();
  });
  todayButton?.addEventListener('click', () => {
    const todayMonth = monthKeyForDate(new Date());
    monthSelect.value = inSeason(todayMonth) ? todayMonth : findInitialMonth();
    renderCalendar();
  });
  resetButton?.addEventListener('click', () => {
    [sportFilter, levelFilter, statusFilter].filter(Boolean).forEach((filter) => { filter.value = 'all'; });
    renderCalendar();
  });

  renderCalendar();
}
