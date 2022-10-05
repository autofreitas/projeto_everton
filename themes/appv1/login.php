<?php $v->layout("_theme"); ?>
<div class="frame-back color-vtsd">
       <div class="flex-center">

        <section class="login">
           <header>
                    <p class="cf-text-8 black cf-text montserrat --color-primary center">ProtePOP</p>
           </header>
           <div class="card color-primary flex wrap mt-10 animate__animated bounceIn">
           <form class="fb-100" action="<?= url("login"); ?>" method="post" enctype="multipart/form-data">
           <?= (empty($csrf)) ? csrf_input() : $csrf ?>
                <div class="campo flex fb-100">
                    <span class="material-icons absolute  --color-secondary">email</span>
                    <input class="pl-40" type="email" name="email" placeholder="Digite o seu e-mail">
                  </div>
                <div class="campo flex fb-100">
                    <span class="material-icons absolute --color-secondary">lock</span>
                    <input  class="pl-40" type="password" name="password" placeholder="Digite a sua senha">
                    <p class="cf-text-3 --red error-msg" id="error-phone"></p>
                </div>
                
                <div class="btn-save flex-center mt-10">
                        <input type="submit" value="ENTRAR" class="btn-default color-secondary --white cf-text-3 flex"/>
                </div>

           </form>
           </div>
        </section>

       </div>
</div>
