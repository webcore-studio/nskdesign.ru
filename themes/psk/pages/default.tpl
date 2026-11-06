{* WebCore CMS Theme for PSK Stroykomplex (Design v4 - 11 Build) *}
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="{mod_getvar modname='SYS_config' name='meta_robots'}">
{if $module_meta_keywords}
  <meta name="keywords" content="{$module_meta_keywords}">
{else}
  <meta name="keywords" content="{mod_getvar|escape modname='SYS_config' name='meta_keywords'}">
{/if}
{if $module_meta_description}
  <meta name="description" content="{$module_meta_description}">
{else}
  <meta name="description" content="{mod_getvar|escape modname='SYS_config' name='meta_description'}">
{/if}
  <meta name="rating" content="{mod_getvar modname='SYS_config' name='meta_rating'}">
  <meta name="author" content="{mod_getvar|escape modname='SYS_config' name='meta_author'}">
  <meta name="copyright" content="{mod_getvar|escape modname='SYS_config' name='meta_copyright'}">

{if $module_meta_title}
  <title>{$module_meta_title}</title>
{elseif $module_browsein}
  <title>
    ПСК СтройКомплекс
    {foreach item=item from=$module_browsein}
      &nbsp;/&nbsp;{$item.displayname|escape}
    {/foreach}
  </title>
{else}
  <title>ПСК СтройКомплекс — проектируем и строим надёжные дома в Сибири</title>
{/if}

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600&family=Outfit:wght@200;400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Onest:wght@500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="/themes/{$user_theme}/style/common.css" type="text/css" media="all">
  <link rel="stylesheet" href="/themes/{$user_theme}/style/icon-bootstrap.css" type="text/css" media="all">
  <link rel="stylesheet" href="/themes/{$user_theme}/style/style.css?{$smarty.now}" type="text/css" media="all">

  <link rel="icon" href="/favicon.ico">
</head>

<body>
{mod_getvar modname='SYS_config' name='site_url' assign="site_url"}
{if !$site_url}{assign var="site_url" value="/"}{/if}

  <!-- Header -->
  <header class="header">
    <div class="wrap">
      <a class="brand" href="{$site_url}" title="ПСК СтройКомплекс">
        <img src="/themes/{$user_theme}/images/logo.png" alt="ПСК СтройКомплекс" style="height:48px; width:auto;">
      </a>

      <nav class="nav">
        <a href="/projects">Проекты домов</a>
        <a href="/work">Наши работы</a>
        <a href="/about">О компании</a>
        <a href="/contacts">Контакты</a>
      </nav>

      <div class="header-contact">
        <svg class="contact-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
          <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/>
          <circle cx="12" cy="10" r="2.5"/>
        </svg>
        <span class="address">г. Новосибирск,<br>ул. Владимира Заровного, 26/3</span>
        <span class="phones">
          <a href="tel:+73832910250">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
            8 (383) 291-02-50
          </a>
          <a href="tel:+79137170019">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
            8 (913) 717-00-19
          </a>
        </span>
        <a href="/contacts" class="btn btn-primary" style="padding: 10px 18px; font-size: 13px;">Заказать звонок</a>
      </div>

      <button class="menu-toggle" id="menuBtn" aria-label="Открыть меню" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>
  </header>

  <!-- Mobile Overlay & Menu -->
  <div class="menu-overlay" id="menuOverlay"></div>
  <div class="mobile-menu" id="mobileNav">
    <a href="/projects">Проекты домов</a>
    <a href="/work">Наши работы</a>
    <a href="/about">О компании</a>
    <a href="/contacts">Контакты</a>
    <div style="padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 10px;">
      <div style="font-size: 13px; color: #9cd1ed; margin-bottom: 8px;">г. Новосибирск, ул. Владимира Заровного, 26/3</div>
      <a href="tel:+73832910250" style="font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:14px; height:14px; color:#9cd1ed;">
          <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
        </svg>
        8 (383) 291-02-50
      </a>
      <a href="tel:+79137170019" style="font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:14px; height:14px; color:#9cd1ed;">
          <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
        </svg>
        8 (913) 717-00-19
      </a>
      <a href="/contacts" class="btn btn-primary" style="margin-top: 12px; text-align: center; display: block;">Заказать звонок</a>
    </div>
  </div>

{if $object == 'MainPage'}
  <!-- Main Landing Canvas -->
  <main>

    <section class="hero">
      <div class="wrap">
        <div class="hero-grid">
          <div class="hero-content">
            <h1>Дом, в котором <em>спокойно</em> жить</h1>
            <p>Проектируем и строим дома из железобетонных панелей, сибита, кирпича, бруса и по каркасной технологии. Фиксируем состав работ и
              смету в договоре.</p>
            <div class="actions">
              <a href="#projects" class="btn-v3 btn-primary-v3">Подобрать проект</a>
              <a href="#work" class="btn-v3 btn-ghost-v3">Посмотреть построенные дома</a>
            </div>
            <div class="hero-proof">
              <div><b>с 2008 года</b>строим в Сибири и на Дальнем Востоке</div>
              <div><b>5 лет</b>гарантия на все работы</div>
            </div>
          </div>
          <div class="hero-image"></div>

        </div>
      </div>
    </section>

    
    <section class="section" id="projects">
      <div class="wrap">
        <div class="section-head reveal">
          <p class="label">Каталог проектов</p>
          <h2>Выберите основу для своего дома</h2>
          <p>Более 40 готовых проектов, которые можно адаптировать под ваши задачи и особенности участка.</p>
        </div>

        <div class="bento">
          <div class="bento-card col-8 reveal">
            <div class="card-photo tall"
              style="background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuAdRBn0H2viHvetyFblIpZtrkFQxJ3j61BRdSIihRoKH_4n31OAYx0gWiP7j_vaKXkrFo0m3qU3Q0pAnlxds5EeICVvtUyYxvt4VMtPuhHoS3WB4FgETjmSzTQOL9aNi8oG8VJ48r31wIRaFEw_v6nnna_Er1Y3VmQG-EE7bEN4bD-AGlseEOah9d9trVhsA9jqBzGWd3O2pzX5SdDyMVIidbUa8AbYncPvav5URkA0ANpyTnYWjh_O-lPYkf_TmLsvKoCAj3pzsG2f')">
            </div>
            <div class="card-body">
              <span class="label card-tag">Популярный выбор</span>
              <h3 class="card-title">Проект «Енисей» — 145 м²</h3>
              <p class="muted">Просторный двухэтажный дом для большой семьи с террасой и панорамным остеклением.</p>
              <div class="card-meta">
                <span>Материал: <b>Кирпич / Сибит</b></span>
                <span>Срок: <b>от 4 месяцев</b></span>
                <span>Цена: <b>от 8,2 млн ₽</b></span>
              </div>
              <a href="/projects" class="card-link">Смотреть проект <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                  stroke-width="2.5">
                  <path d="M5 12h14M12 5l7 7-7 7" />
                </svg></a>
            </div>
          </div>

          <div class="bento-card col-4 calc-card reveal" id="calc">
            <p class="label" style="color:#9cd1ed;">Калькулятор дома</p>
            <h3>Рассчитать стоимость</h3>
            <div class="calc-fields">
              <div class="calc-field">
                <label for="calcArea">Площадь дома</label>
                <select id="calcArea">
                  <option value="90">до 100 м² (компактный)</option>
                  <option value="125">100–140 м² (семейный)</option>
                  <option value="145" selected>145 м² (проект «Енисей»)</option>
                  <option value="180">150–200 м² (комфорт)</option>
                  <option value="240">от 200 м² (усадьба)</option>
                </select>
              </div>
              <div class="calc-field">
                <label for="calcMaterial">Материал стен</label>
                <select id="calcMaterial">
                  <option value="48000" selected>Сибит / газобетон (от 48 тыс./м²)</option>
                  <option value="52000">ЖБ-панели (быстрый монтаж)</option>
                  <option value="62000">Кирпич классический</option>
                  <option value="45000">Брус профилированный</option>
                  <option value="39000">Каркасная технология</option>
                </select>
              </div>
              <div class="calc-field">
                <label for="calcPackage">Комплектация</label>
                <select id="calcPackage">
                  <option value="1.0">Тёплый контур (коробка + кровля + окна)</option>
                  <option value="1.35" selected>Под чистовую отделку (+ инженерия)</option>
                  <option value="1.65">Под ключ (полная отделка)</option>
                </select>
              </div>
            </div>
            <div class="calc-result">
              <span class="label">Ориентировочно:</span>
              <span class="amount" id="calcAmount">от 7.1 млн ₽</span>
            </div>
            <a href="#contacts" class="btn btn-primary" id="calcSubmitBtn" style="width:100%; text-align:center; display:block; padding:14px;">Получить точную смету</a>
          </div>

          <div class="bento-card col-4 reveal">
            <div class="card-photo"
              style="background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuBpHT7FdxznVNzY8y3gbM8HS0GWWWmUcNXrkSxeYFttx4MwZ6OpgYz_btadf0VigJ0QrUdkRFAAlc3RhLLnrxNeOozrJzJrzKng-7T735G-UopIkpGZovz-3S6Qkcb2uVoiljSnC745kxsWhayqI-5vNwUOoE115X6F5F16lDesrhO_AFGuWf90d754vu0tgGKw50bZWygfPtoxc-dZkmLAgPPAjaOt3sYcUyVGSoYCuU5DtXhSLAIs0u5EVzHJQocVChFSh5K9MYbM')">
            </div>
            <div class="card-body">
              <h3 class="card-title">Проект «Обь»</h3>
              <p class="muted">Компактный и функциональный дом 110 м².</p>
              <a href="/projects" class="card-link">Детали <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                  stroke-width="2.5">
                  <path d="M5 12h14M12 5l7 7-7 7" />
                </svg></a>
            </div>
          </div>

          <div class="bento-card col-4 reveal">
            <div class="card-photo"
              style="background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuD9p5UOM4y-4lY6J3MBmMoQLevN5VmdQ5pDiKXpTSbrED1vDkeT06WeXg6dMUrPqq5813rEcYY4rx6UyskgbX4rAp88hHQ6RfgdGMG7r6DvgVnnIJPz-UTHSMotf4OxLOoz4qTgxCgprKFRX54wfiresBJ8BeJ8HcB3BbfHxAHYMQrUvLxt6MKoaH6dZujBgwX3iC8PTf1J_MbvRlu6muNSHPod22AZ3k0Ej81uXUufxU_fWJ9Enwh9yRZ2yws-DFAjTn3adSj9VUE5')">
            </div>
            <div class="card-body">
              <h3 class="card-title">Проект «Алтай»</h3>
              <p class="muted">Премиальное решение 220 м².</p>
              <a href="/projects" class="card-link">Детали <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                  stroke-width="2.5">
                  <path d="M5 12h14M12 5l7 7-7 7" />
                </svg></a>
            </div>
          </div>

          <div class="bento-card col-4 reveal"
            style="background:var(--blue-pale);display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;padding:40px">
            <h3 class="card-title">Индивидуальный проект</h3>
            <p class="muted">Разработаем дом с нуля под ваши требования</p>
            <a href="#" class="btn btn-ghost" style="margin-top:20px">Обсудить идею</a>
          </div>
        </div>
      </div>
    </section>

    
    <section class="section">
      <div class="wrap">
        <div class="section-head reveal">
          <p class="label">Наши технологии</p>
          <h2>Строим из проверенных материалов</h2>
          <p>Мы используем только надежные и современные технологии строительства, обеспечивающие долговечность и комфорт вашего дома.</p>
        </div>
        <div class="bento">
          <div class="bento-card col-4 reveal">
            <div class="card-body">
              <h3 class="card-title">Дома из сибита</h3>
              <p class="muted">Энергоэффективные и экологичные дома из автоклавного газобетона.</p>
              <a href="#" class="card-link">Подробнее <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7" /></svg></a>
            </div>
          </div>
          <div class="bento-card col-4 reveal">
            <div class="card-body">
              <h3 class="card-title">Кирпичные дома</h3>
              <p class="muted">Классика надежности и долговечности, проверенная временем.</p>
              <a href="#" class="card-link">Подробнее <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7" /></svg></a>
            </div>
          </div>
          <div class="bento-card col-4 reveal">
            <div class="card-body">
              <h3 class="card-title">Каркасные дома</h3>
              <p class="muted">Быстровозводимые и теплые дома по современным технологиям.</p>
              <a href="#" class="card-link">Подробнее <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7" /></svg></a>
            </div>
          </div>
        </div>
      </div>
    </section>

    
    <section class="section" id="work">
      <div class="wrap">
        <div class="section-head reveal">
          <p class="label">Наши работы</p>
          <h2>Посмотрите построенные нами дома</h2>
          <p>Каждый проект — это воплощение мечты наших клиентов в реальность. Оцените качество и подход.</p>
        </div>
        <div class="bento">
          <div class="bento-card col-6 reveal">
            <div class="card-photo tall" style="background-image:url(\'/themes/{$user_theme}/images/project-1.jpg\')"></div>
            <div class="card-body">
              <h3 class="card-title">Дом в КП "Солнечный"</h3>
              <p class="muted">Проект "Енисей" из сибита, 145 м².</p>
              <div class="card-meta">
                <span>Материал: <b>Сибит</b></span>
                <span>Площадь: <b>145 м²</b></span>
                <span>Срок: <b>4 месяца</b></span>
              </div>
              <a href="/work" class="card-link">Подробнее <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7" /></svg></a>
            </div>
          </div>
          <div class="bento-card col-6 reveal">
            <div class="card-photo tall" style="background-image:url(\'/themes/{$user_theme}/images/project-2.jpg\')"></div>
            <div class="card-body">
              <h3 class="card-title">Коттедж в "Березовой роще"</h3>
              <p class="muted">Индивидуальный проект, кирпич, 220 м².</p>
              <div class="card-meta">
                <span>Материал: <b>Кирпич</b></span>
                <span>Площадь: <b>220 м²</b></span>
                <span>Срок: <b>6 месяцев</b></span>
              </div>
              <a href="/work" class="card-link">Подробнее <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7" /></svg></a>
            </div>
          </div>
        </div>
        <div style="text-align:center; margin-top: 40px;">
          <a href="/work" class="btn btn-ghost">Смотреть все работы</a>
        </div>
      </div>
    </section>

    
    <section class="section">
      <div class="wrap">
        <div class="section-head reveal">
          <p class="label">Финансовые решения</p>
          <h2>Постройте дом мечты с выгодной ипотекой</h2>
          <p>Мы поможем подобрать оптимальную программу кредитования и оформить все необходимые документы.</p>
        </div>
        <div class="bento">
          <div class="bento-card col-4 reveal">
            <div class="card-body">
              <h3 class="card-title">Семейная ипотека</h3>
              <p class="muted">Выгодные условия для семей с детьми.</p>
              <a href="#" class="card-link">Узнать больше <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7" /></svg></a>
            </div>
          </div>
          <div class="bento-card col-4 reveal">
            <div class="card-body">
              <h3 class="card-title">IT-ипотека</h3>
              <p class="muted">Специальные условия для IT-специалистов.</p>
              <a href="#" class="card-link">Узнать больше <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7" /></svg></a>
            </div>
          </div>
          <div class="bento-card col-4 reveal">
            <div class="card-body">
              <h3 class="card-title">Сельская ипотека</h3>
              <p class="muted">Поддержка строительства в сельской местности.</p>
              <a href="#" class="card-link">Узнать больше <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7" /></svg></a>
            </div>
          </div>
          <div class="bento-card col-12 reveal" style="background:var(--blue-pale);display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;padding:40px">
            <h3 class="card-title">Другие способы оплаты</h3>
            <p class="muted">Наличный расчет, материнский капитал, беспроцентная рассрочка.</p>
            <a href="#" class="btn btn-ghost" style="margin-top:20px">Все варианты</a>
          </div>
        </div>
      </div>
    </section>

    
    <section class="section trust" id="about">
      <div class="wrap">
        <div class="trust-grid">
          <div class="trust-head reveal">
            <p class="label">Почему выбирают нас</p>
            <h2>Строим как для себя —<br><strong>честно и надолго</strong></h2>
            <p>За 9 лет работы мы сформировали стандарты, которые позволяют нам гарантировать качество на каждом этапе
              строительства.</p>
            <div style="margin-top:40px">
              <a href="about.html" class="btn btn-ghost">Подробнее о компании</a>
            </div>
          </div>
          <div class="trust-reasons">
            <div class="trust-item reveal">
              <span class="trust-num">01</span>
              <div>
                <h4>Прозрачная смета</h4>
                <p>Фиксируем стоимость материалов и работ в договоре. Никаких скрытых платежей и внезапных подорожаний в
                  процессе.</p>
              </div>
            </div>
            <div class="trust-item reveal">
              <span class="trust-num">02</span>
              <div>
                <h4>Технический надзор</h4>
                <p>Собственная служба контроля качества проверяет каждый этап: от вязки арматуры до монтажа кровли.</p>
              </div>
            </div>
            <div class="trust-item reveal">
              <span class="trust-num">03</span>
              <div>
                <h4>Собственные бригады</h4>
                <p>Мы не нанимаем случайных людей. У нас работают проверенные мастера с опытом в сибирском домостроении
                  от 7 лет.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    
    <section class="section">
      <div class="wrap">
        <div class="section-head reveal">
          <p class="label">Как мы работаем</p>
          <h2>Прозрачный процесс строительства</h2>
          <p>Мы разделили весь процесс на понятные этапы, чтобы вы всегда знали, что происходит с вашим будущим домом.</p>
        </div>
        <div class="trust-reasons">
          <div class="trust-item reveal">
            <span class="trust-num">01</span>
            <div>
              <h4>Консультация и проектирование</h4>
              <p>Обсуждаем ваши идеи, подбираем проект или разрабатываем индивидуальный.</p>
            </div>
          </div>
          <div class="trust-item reveal">
            <span class="trust-num">02</span>
            <div>
              <h4>Расчет сметы и договор</h4>
              <p>Фиксируем стоимость и сроки, заключаем официальный договор.</p>
            </div>
          </div>
          <div class="trust-item reveal">
            <span class="trust-num">03</span>
            <div>
              <h4>Подготовительные работы</h4>
              <p>Подготовка участка, закладка фундамента.</p>
            </div>
          </div>
          <div class="trust-item reveal">
            <span class="trust-num">04</span>
            <div>
              <h4>Строительство</h4>
              <p>Возведение стен, кровли, монтаж инженерных систем.</p>
            </div>
          </div>
          <div class="trust-item reveal">
            <span class="trust-num">05</span>
            <div>
              <h4>Внутренняя и внешняя отделка</h4>
              <p>Чистовая отделка дома по вашему дизайн-проекту.</p>
            </div>
          </div>
          <div class="trust-item reveal">
            <span class="trust-num">06</span>
            <div>
              <h4>Сдача объекта</h4>
              <p>Приемка готового дома, подписание актов и передача ключей.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    
    <section class="section trust" style="background: var(--bg);">
      <div class="wrap">
        <div class="section-head reveal">
          <p class="label">Что говорят о нас</p>
          <h2>Отзывы наших довольных клиентов</h2>
          <p>Лучшее подтверждение нашей работы — это счастливые семьи в новых домах.</p>
        </div>
        <div class="bento">
          <div class="bento-card col-6 reveal">
            <div class="card-body">
              <h3 class="card-title">Ирина С.</h3>
              <p class="muted">"Очень довольны работой компании! Построили дом быстро и качественно, все наши пожелания были учтены. Рекомендуем!"</p>
              <div class="card-meta">
                <span>Проект: <b>"Енисей"</b></span>
                <span>Дата: <b>Май 2024</b></span>
              </div>
            </div>
          </div>
          <div class="bento-card col-6 reveal">
            <div class="card-body">
              <h3 class="card-title">Александр П.</h3>
              <p class="muted">"Благодарим ПСК СтройКомплекс за наш уютный дом. Отличное качество материалов и профессионализм бригады."</p>
              <div class="card-meta">
                <span>Проект: <b>Индивидуальный</b></span>
                <span>Дата: <b>Июнь 2024</b></span>
              </div>
            </div>
          </div>
        </div>
        <div style="text-align:center; margin-top: 40px;">
          <a href="#" class="btn btn-ghost">Читать все отзывы</a>
        </div>
      </div>
    </section>

    
    <section class="section">
      <div class="wrap">
        <div class="section-head reveal">
          <p class="label">Вопросы и ответы</p>
          <h2>Часто задаваемые вопросы</h2>
          <p>Мы собрали ответы на самые популярные вопросы, чтобы помочь вам принять решение.</p>
        </div>
        <div class="faq-list">
          <div class="faq-item reveal">
            <button class="faq-question" aria-expanded="false">
              <span>Какие гарантии вы предоставляете?</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="faq-icon">
                <path d="M6 9l6 6 6-6" />
              </svg>
            </button>
            <div class="faq-answer">
              <p>Мы предоставляем 5 лет гарантии на все выполненные работы и конструкции дома.</p>
            </div>
          </div>
          <div class="faq-item reveal">
            <button class="faq-question" aria-expanded="false">
              <span>Можно ли построить дом в ипотеку?</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="faq-icon">
                <path d="M6 9l6 6 6-6" />
              </svg>
            </button>
            <div class="faq-answer">
              <p>Да, мы работаем со всеми ведущими банками и помогаем с оформлением ипотеки.</p>
            </div>
          </div>
          <div class="faq-item reveal">
            <button class="faq-question" aria-expanded="false">
              <span>Сколько времени занимает строительство?</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="faq-icon">
                <path d="M6 9l6 6 6-6" />
              </svg>
            </button>
            <div class="faq-answer">
              <p>Сроки зависят от сложности проекта и материалов, в среднем от 3 до 6 месяцев.</p>
            </div>
          </div>
          <div class="faq-item reveal">
            <button class="faq-question" aria-expanded="false">
              <span>Можно ли внести изменения в типовой проект?</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="faq-icon">
                <path d="M6 9l6 6 6-6" />
              </svg>
            </button>
            <div class="faq-answer">
              <p>Да, мы готовы адаптировать любой типовой проект под ваши индивидуальные пожелания.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    
    <section class="section cta" id="contacts">
      <div class="wrap">
        <div class="reveal">
          <h2>Готовы начать<br><strong>строительство?</strong></h2>
          <p>Оставьте заявку — наш инженер свяжется с вами в течение часа, чтобы обсудить проект и подготовить
            предварительный расчёт.</p>
        </div>
        <form class="cta-form reveal">
          <input type="text" placeholder="Ваше имя" aria-label="Имя">
          <input type="tel" placeholder="+7 (___) ___-__-__" aria-label="Телефон">
          <label class="cta-consent">
            <input type="checkbox" required>
            <span>Я согласен на обработку персональных данных в соответствии с <a href="/privacy" target="_blank">Политикой
                конфиденциальности</a>.</span>
          </label>
          <button type="submit" class="btn btn-primary">Отправить заявку</button>
        </form>
      </div>
    </section>
  
  </main>

  {if $blocks.bottom}
  <div class="wrap" style="padding-bottom: 40px;">
    {foreach item=block from=$blocks.bottom}
      <div class="block-item">{$block.block_content}</div>
    {/foreach}
  </div>
  {/if}

{else}
  <!-- Inner Page Canvas -->
  <main class="wrap psk-inner-canvas">
    {if $module_browsein}
    <div class="browsein">
      {browsein date=$module_browsein show_last=false href_last=true}
      <h1>{browsein date=$module_browsein lastname_only=true}</h1>
    </div>
    {/if}

    {if $blocks.top}
    <div class="blocks-top" style="margin-bottom: 24px;">
      {foreach item=block from=$blocks.top}
        <div class="block-item">{$block.block_content}</div>
      {/foreach}
    </div>
    {/if}

    <div class="psk-inner-content">
      {$module_content}
    </div>

    {if $blocks.bottom}
    <div class="blocks-bottom" style="margin-top: 36px;">
      {foreach item=block from=$blocks.bottom}
        <div class="block-item">{$block.block_content}</div>
      {/foreach}
    </div>
    {/if}
  </main>
{/if}

  <!-- Footer -->
  <footer class="footer">
    <div class="wrap">
      <div class="footer-brand">
        <b>ПСК СтройКомплекс</b><br>
        г. Новосибирск, ул. Владимира Заровного, д. 26/3<br>
        ООО «ПСК СК» &middot; ИНН 5406830500 &middot; ОГРН 1235400015500<br>
        <span style="color:#888;">Проектирование и строительство жилых домов с 2008 года.</span>
      </div>
      <div class="footer-links">
        <div>
          <a href="/projects">Проекты домов</a>
          <a href="/work">Наши работы</a>
          <a href="/about">О компании</a>
          <a href="/news">Новости</a>
        </div>
        <div>
          <a href="/#calc">Калькулятор стоимости</a>
          <a href="/faq">Вопрос-ответ</a>
          <a href="/contacts">Контакты</a>
          <a href="/privacy">Политика конфиденциальности</a>
        </div>
      </div>
      <div class="footer-bottom" style="width:100%;">
        <span>&copy; 2008–2026 ООО «ПСК СК». Все права защищены.</span>
        <span>Разработка сайта — <a href="https://nskdesign.ru" target="_blank" rel="noopener" style="color:inherit; text-decoration:underline;">NSK Design</a></span>
      </div>
    </div>
  </footer>

  <!-- Back to Top Button -->
  <div class="back-to-top" id="backToTop" aria-label="Наверх" role="button" tabindex="0">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
      <path d="M18 15l-6-6-6 6" />
    </svg>
  </div>

{literal}
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu & Overlay
    const menuBtn = document.getElementById('menuBtn');
    const mobileNav = document.getElementById('mobileNav');
    const menuOverlay = document.getElementById('menuOverlay');

    function toggleMenu() {
      const isOpen = mobileNav.classList.toggle('open');
      menuBtn.classList.toggle('open', isOpen);
      menuOverlay.classList.toggle('open', isOpen);
      document.body.classList.toggle('menu-open', isOpen);
      menuBtn.setAttribute('aria-expanded', String(isOpen));
    }

    if (menuBtn && menuOverlay && mobileNav) {
      menuBtn.addEventListener('click', toggleMenu);
      menuOverlay.addEventListener('click', toggleMenu);
      mobileNav.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
        if (mobileNav.classList.contains('open')) toggleMenu();
      }));
    }

    // Back to Top
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
      window.addEventListener('scroll', () => {
        if (window.scrollY > 500) {
          backToTop.classList.add('visible');
        } else {
          backToTop.classList.remove('visible');
        }
      });
      backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }

    // Scroll reveal
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    // FAQ Accordion
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(button => {
      button.addEventListener('click', function() {
        const isExpanded = this.getAttribute('aria-expanded') === 'true';
        const answer = this.nextElementSibling;
        
        faqQuestions.forEach(otherButton => {
          if (otherButton !== button) {
            otherButton.setAttribute('aria-expanded', 'false');
            if (otherButton.nextElementSibling) otherButton.nextElementSibling.classList.remove('open');
          }
        });
        
        this.setAttribute('aria-expanded', !isExpanded);
        if (answer) answer.classList.toggle('open', !isExpanded);
      });
    });

    // Dynamic Live Calculator
    const areaSel = document.getElementById('calcArea');
    const matSel = document.getElementById('calcMaterial');
    const packSel = document.getElementById('calcPackage');
    const amountSpan = document.getElementById('calcAmount');

    function updateCalculator() {
      if (!areaSel || !matSel || !packSel || !amountSpan) return;
      const area = parseFloat(areaSel.value) || 120;
      const pricePerMeter = parseFloat(matSel.value) || 48000;
      const multiplier = parseFloat(packSel.value) || 1.35;
      
      const totalRub = area * pricePerMeter * multiplier;
      const millions = (totalRub / 1000000).toFixed(1);
      amountSpan.textContent = 'от ' + millions + ' млн ₽';
    }

    if (areaSel && matSel && packSel) {
      areaSel.addEventListener('change', updateCalculator);
      matSel.addEventListener('change', updateCalculator);
      packSel.addEventListener('change', updateCalculator);
      updateCalculator();
    }
  });
</script>
{/literal}

</body>
</html>
