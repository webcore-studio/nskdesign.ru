'use strict';

(() => {
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.getElementById('site-nav');
  const closeMenu = () => {
    if (!toggle || !nav) return;
    nav.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-label', 'Открыть меню');
  };
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const opened = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(opened));
      toggle.setAttribute('aria-label', opened ? 'Закрыть меню' : 'Открыть меню');
    });
    nav.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape') closeMenu();
    });
    document.addEventListener('click', event => {
      if (!nav.contains(event.target) && !toggle.contains(event.target)) closeMenu();
    });
  }

  const filters = document.querySelectorAll('[data-case-filter]');
  const cards = document.querySelectorAll('[data-case-category]');
  filters.forEach(button => {
    button.addEventListener('click', () => {
      filters.forEach(filter => {
        const selected = filter === button;
        filter.classList.toggle('is-active', selected);
        filter.setAttribute('aria-pressed', String(selected));
      });
      cards.forEach(card => {
        card.hidden = button.dataset.caseFilter !== 'all' && button.dataset.caseFilter !== card.dataset.caseCategory;
      });
    });
  });

  document.querySelectorAll('[data-dismiss-message]').forEach(button => {
    button.addEventListener('click', () => button.closest('.site-message').remove());
  });

  const form = document.getElementById('digital-order-form');
  if (form) {
    form.addEventListener('submit', event => {
      event.preventDefault();
      if (!form.reportValidity()) return;
      const data = new FormData(form);
      const subject = `Заявка с nskdesign.ru: ${data.get('project_type')}`;
      const body = [
        `Имя: ${data.get('user_name')}`,
        `Контакт: ${data.get('user_contact')}`,
        `Тип задачи: ${data.get('project_type')}`,
        '',
        String(data.get('user_task') || '')
      ].join('\r\n');
      window.location.href = `mailto:info@nskdesign.ru?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
      document.getElementById('order-status').textContent = 'Откроется почтовая программа. Отправьте подготовленное письмо. Если она не открылась, напишите на info@nskdesign.ru или в мессенджер.';
    });
  }
})();
