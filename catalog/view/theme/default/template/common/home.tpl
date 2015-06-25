<?php echo $header; ?>

      <table class="page-table">
        <tr>
          <td valign="middle">
            <div id="main" role="main">
                <div class="logo"><a href="#"><img src="catalog/view/theme/default/images/logo.png" alt="Accent Logistics"></a></div>

                <a href="#" class="about links show_info">О нас</a>
              <div class="services links">
                    <form>
                        <div id="search">
                            <input class="input-lg" type="text" placeholder="№ посылки" name="pack_id" id="pack-id" value="<?php echo $pack_id; ?>">
                            <input type="button"  value="Поиск" class="btn btn-lg">
                        </div>
                    </form>
                    <?php echo $pack_data; ?>
                </div>
                <!--<p class="rrr"><img src="catalog/view/theme/default/images/plane.png" width="32" height="24" alt=""/> &nbsp;&nbsp;Ближайший вылет 1 апреля</p>-->
                <a href="#" class="contacts links show_info">Контакты</a>
              <div class="main-bg"></div>
                <div class="about-hover"></div>
                <div class="serv-hover"></div>
                <div class="vacancies-hover"></div>
                <div class="contacts-hover"></div>
               <!-- <div class="planet"></div>-->
               <!-- <div class="under-planet-bg"></div>-->
                <div class="about-bg"></div>
                <div class="contacts-bg"></div>
                <!--<div class="partners-bg"></div>-->
                <div class="services-bg"></div>
                <div class="scroll-pane"></div>
            </div>
          </td>
        </tr>
      </table>
      <div class="popup">
        <div class="popup-content">
            
          </div>
        <div class="close"></div>
      </div>
      <div id="bott_height">&nbsp;</div>
<?php echo $footer; ?>