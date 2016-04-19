<div id="center-panel">

    <form action="" method="POST">

        <div class="form-block">
            <span for="callback_user_name">Ваше имя:</span>
            <input type="text" name="callback_user_name" maxlength="40" id="callback_user_name"/>
        </div>

        <div class="form-block">
            <span for="callback_user_phone">Ваш Телефон:</span>
            <input type="text" name="callback_user_phone" maxlength="20" id="callback_user_phone"/>
        </div>

        <div class="form-block">
            <span class="message-operator">НАШ ОПЕРАТОР СВЯЖЕТСЯ С ВАМИ В ТЕЧЕНИЕ 3 МИНУТ</span>
        </div>

        <div class="form-block button-section">
            <input type="hidden" name="action" value="add-callback"/>
            <input class="but-form" type="submit" name="send" value="Заказать звонок"/>

            <!-- <?php /*if(isset( $_POST['send'])) {
          if($msg!='')
          {*/ ?>
              <div class="erroroutput"><p><?php /*echo $msg; */ ?><br></p></div>

          <?php /*} else{*/ ?>
              <div><center><span>НАШ ОПЕРАТОР СВЯЖЕТСЯ С ВАМИ В ТЕЧЕНИЕ 3 МИНУТ</span></center></div>
          --><?php /*}
      }*/ ?>

        </div>


    </form>

</div> <!-- #service-list -->
