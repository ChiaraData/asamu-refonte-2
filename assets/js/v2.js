const v2MenuToggle = document.querySelector('[data-v2-menu-toggle]');
const v2MenuPanel = document.querySelector('[data-v2-menu-panel]');

if (v2MenuToggle && v2MenuPanel) {
  const setV2Menu = (isOpen) => {
    v2MenuToggle.setAttribute('aria-expanded', String(isOpen));
    v2MenuPanel.setAttribute('aria-hidden', String(!isOpen));
    v2MenuPanel.classList.toggle('is-open', isOpen);
    document.body.classList.toggle('v2-menu-open', isOpen);
  };

  v2MenuToggle.addEventListener('click', () => {
    setV2Menu(v2MenuToggle.getAttribute('aria-expanded') !== 'true');
  });

  v2MenuPanel.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => setV2Menu(false));
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') setV2Menu(false);
  });
}

document.querySelectorAll('[data-news-load-more]').forEach((button) => {
  const grid = document.getElementById(button.dataset.newsTarget || '');
  const cards = grid ? [...grid.querySelectorAll('[data-news-card]')] : [];
  const status = button.parentElement?.querySelector('[data-news-status]');
  const step = Math.max(1, Number.parseInt(button.dataset.newsStep || '3', 10) || 3);

  if (!grid || cards.length <= step) return;

  let visibleCount = step;

  const updateButton = () => {
    const remaining = cards.length - visibleCount;
    button.hidden = remaining <= 0;

    if (remaining > 0) {
      const count = Math.min(step, remaining);
      button.textContent = `Voir ${count} actualité${count > 1 ? 's' : ''} de plus`;
    }
  };

  cards.slice(visibleCount).forEach((card) => {
    card.hidden = true;
    card.setAttribute('aria-hidden', 'true');
  });

  button.addEventListener('click', () => {
    const nextCards = cards.slice(visibleCount, visibleCount + step);

    nextCards.forEach((card) => {
      card.hidden = false;
      card.removeAttribute('aria-hidden');
    });

    visibleCount += nextCards.length;
    updateButton();

    if (status) {
      status.textContent = `${nextCards.length} actualité${nextCards.length > 1 ? 's' : ''} supplémentaire${nextCards.length > 1 ? 's' : ''} affichée${nextCards.length > 1 ? 's' : ''}.`;
    }
  });

  button.hidden = false;
  updateButton();
});

document.querySelectorAll('[data-palmares-load-more]').forEach((button) => {
  const grid = document.getElementById(button.dataset.palmaresTarget || '');
  const cards = grid ? [...grid.querySelectorAll('[data-palmares-sport-card]')] : [];
  const status = button.parentElement?.querySelector('[data-palmares-status]');
  const step = Math.max(1, Number.parseInt(button.dataset.palmaresStep || '3', 10) || 3);

  if (!grid || cards.length <= step) return;

  let visibleCount = step;

  const updateButton = () => {
    const remaining = cards.length - visibleCount;
    button.hidden = remaining <= 0;

    if (remaining > 0) {
      const count = Math.min(step, remaining);
      button.textContent = `Voir ${count} discipline${count > 1 ? 's' : ''} de plus`;
    }
  };

  cards.slice(visibleCount).forEach((card) => {
    card.hidden = true;
    card.setAttribute('aria-hidden', 'true');
  });

  button.addEventListener('click', () => {
    const nextCards = cards.slice(visibleCount, visibleCount + step);

    nextCards.forEach((card) => {
      card.hidden = false;
      card.removeAttribute('aria-hidden');
    });

    visibleCount += nextCards.length;
    updateButton();

    if (status) {
      status.textContent = `${nextCards.length} discipline${nextCards.length > 1 ? 's' : ''} supplémentaire${nextCards.length > 1 ? 's' : ''} affichée${nextCards.length > 1 ? 's' : ''}.`;
    }
  });

  button.hidden = false;
  updateButton();
});
