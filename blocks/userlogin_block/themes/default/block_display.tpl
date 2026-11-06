{*debug*}

{if !$loggedin}
	<div class="col-lg-5 f-main">
              <!-- Signup Form -->
              <div class="card bg-light mb-3">
  
                <div class="card-body">
				<form class="js-validate" method="POST" action="/index.php?module=SYS_users&amp;func=login">
				<input type="hidden" name="http_referer" value="">
                  <div class="mb-4">
                    <h2 class="h4 text-dark">Вход</h2>
                  </div>



                  <!-- Input -->
                  <div class="js-form-message mb-3">
                    <div class="js-focus-state input-group input-group form">
                      <input type="text" class="form-control form__input" name="user_name"  placeholder="Введите email"  autofocus="">
                    </div>
                  </div>
                  <!-- End Input -->

                  <!-- Input -->
                  <div class="js-form-message mb-3">
                    <div class="js-focus-state input-group input-group form">
                      <input type="password" class="form-control form__input" name="user_pass" required="" placeholder="Введите пароль" aria-label="Enter your password">
                    </div>
                  </div>
                  <!-- End Input -->

                  <button type="submit" class="btn btn-block btn-primary" name="login">Войти</button>
				  
                </form></div>
                <div style="font-size: 1rem;"><a href="/index.php?module=SYS_users&amp;func=lostpassscreen" style="float: right;padding: 0 1.25rem 1.25rem 1.25rem;">Забыли пароль?</a></div>
              </div>
              <!-- End Signup Form -->
            </div>


{else}
<div style="float: right;">
	<div id="cabinet">
		<span style="color: white;"><b>
			{if $user_vars.user_displayname}{$user_vars.user_displayname} ({$user_vars.user_name})
			{elseif $user_vars.user_surname}{$user_vars.user_surname} {$user_vars.user_firstname} {$user_vars.user_secondname}
			{else}{$user_vars.user_name}
			{/if}
		</b></span>

		{if $curuser_sec_level < 70}
    <span><a href="/index.php?module=content" style="color: white;">Разделы</a></span>
    <span><a href="/index.php?module=edu&func=score_list" style="color: white;">Оценки</a></span>{/if}
		<span><a href="/logout" style="color: white;">Выход</a></span>
	</div>
</div>
{/if}

