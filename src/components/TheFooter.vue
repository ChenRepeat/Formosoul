<script setup>
import siteLogo from '@/assets/LOGO_white_footer.svg'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
</script>

<template>
  <main class="footer-case">
    <section class="footer-outer-case dp-flex">
      <div class="footer-icons-case dp-flex-col">
        <img :src="siteLogo" alt="Logo" class="logo-style" />
        <div class="footer-social-link dp-flex">
          <a href="https://github.com/ChenRepeat/Formosoul" class=" no-i18n-anim"
            ><font-awesome-icon
              icon="fa-brands fa-github"
              class="footer-socialIcons"
          /></a>
          <a href="https://instagram.com/"  class=" no-i18n-anim"
            ><font-awesome-icon
              icon="fa-brands fa-instagram"
              class="footer-socialIcons no-i18n-anim"
          /></a>
          <a href="https://facebook.com/"  class=" no-i18n-anim"
            ><font-awesome-icon
              icon="fa-brands fa-square-facebook"
              class="footer-socialIcons no-i18n-anim"
          /></a>
          <a href="https://x.com/"  class=" no-i18n-anim"
            ><font-awesome-icon
              icon="fa-brands fa-x-twitter"
              class="footer-socialIcons no-i18n-anim"
          /></a>
        </div>
      </div>

      <div class="footer-link-corner dp-flex">
        <div class="footer-link-case">
          <div class="trim-case-link dp-flex-col">
            <hr />
            <hr class="hr-thick" />
          </div>
          <div class="footer-inner-case dp-flex">
            <ul class="footer-page-link dp-flex-col">
              <li>
                <router-link to="/"><h5>{{$t('nav.home')}}</h5></router-link>
              </li>
              <li>
                <router-link to="/news"><h5>{{$t('nav.news')}}</h5></router-link>
              </li>
              <li>
                <router-link to="/annualevent"><h5>{{$t('nav.annualEvent')}}</h5></router-link>
              </li>
              <li>
                <router-link to="/professorsintroduction"><h5>{{$t('nav.professor')}}</h5></router-link>
              </li>
              <li>
                <router-link to="/survivalrules"><h5>{{$t('nav.survivalGuide')}}</h5></router-link>
              </li>
              <li>
                <router-link to="/shop"><h5>{{$t('nav.diagonAlley')}}</h5></router-link>
              </li>
              <li>
                <router-link to="/about"><h5>{{$t('nav.about')}}</h5></router-link>
              </li>
              <li>
                <router-link to="/member"><h5>{{$t('nav.member')}}</h5></router-link>
              </li>
            </ul>
            <ul class="footer-classes-link dp-flex-col">
              <li class="footer-class-link-title">
                <router-link to="/classes"><h5>{{$t('nav.classes')}}</h5></router-link>
              </li>
              <li>
                <a href=""><p>Motorcycle</p></a>
              </li>
              <li>
                <a href=""><p>Endemic Species</p></a>
              </li>
              <li>
                <a href=""><p>History of Magic</p></a>
              </li>
              <li>
                <a href=""><p>Spells & Charms</p></a>
              </li>
              <li>
                <a href=""><p>Potions</p></a>
              </li>
              <li>
                <a href=""><p>Divination</p></a>
              </li>
              <li></li>
            </ul>
          </div>
        </div>
        <div class="footer-policy-case">
          <div class="trim-case-policy dp-flex-col">
            <hr />
            <hr class="hr-thick" />
          </div>
          <ul class="policy-inner-case dp-flex-col">
            <li>
              <router-link to="/policy"><h5>{{$t('nav.policy')}}</h5></router-link>
            </li>
            <li>
              <router-link to="/policy/payment"><p>Payment</p></router-link>
            </li>
            <li>
              <router-link to="/policy/delivery"><p>Delivery</p></router-link>

            </li>
            <li>
              <router-link to="/policy/returns"><p>Returns</p></router-link>
            </li>
            <li>
              <router-link to="/policy/privacypolicy"><p>Privacy Policy</p></router-link>
            </li>
          </ul>
        </div>
      </div>
    </section>
    <div class="tibame-policy">
      本網站為緯育TibaMe_前端工程師班第97期學員專題成果作品，本平台僅供學習、展示之用。<br />若有抵觸有關著作權，或有第三人主張侵害智慧財產權等情事，均由學員負法律上責任，緯育公司概不負責。若有侵權疑慮，您可以私訊「<a
        href=""
        >緯育TibaMe</a
      >」，後續會由專人協助處理。
    </div>
  </main>
</template>

<style scoped lang="scss">
.footer-link-corner a {
  width: 100%;
  display: block;
}
ul {
  list-style: none;
}
hr {
  margin: 0;
}
.footer-icons-case {
  height: auto;
  justify-content: center;
  gap: 60px;
  width: 40%;
}
.logo-style {
  align-self: center;
}
.footer-link-corner {
  gap: 120px;
  width: 60%;
}
.footer-case {
  background-color: #041426;
  padding-top: 60px;
  padding-bottom: 16px;
  height: 100%;
  width: 100%;
  z-index: 2;
  position: relative;
}
.footer-outer-case {
  margin: 0 auto;
  width: 1200px;
  gap: 100px;
  justify-content: center;
}
.footer-inner-case {
  justify-content: start;
  gap: 120px;
}
.footer-social-link {
  text-align: center;
  gap: 8px;
  justify-content: center;
}
.footer-socialIcons {
  font-size: 32px;
  color: $color-fsWhite;
  cursor: pointer;
}

.footer-page-link {
  min-width: 185px;
  gap: 14px;
  padding: 0;
}
.footer-link-case {
  width: auto;
  position: relative;
}
.footer-classes-link {
  gap: 16px;
  padding: 0;
}
.footer-class-link-title {
  margin-bottom: 8px;
  font-size: 28px;
}
.footer-policy-case {
  width: auto;
  position: relative;
}
.footer-policy-case ul {
  padding: 0;
  max-width: 106px;
}
.tibame-policy {
  text-align: center;
  font-size: 12px;
  color: #b0b0b0;
  margin-top: 60px;
}
.trim-case-policy,
.trim-case-link {
  gap: 8px;
  margin-bottom: 40px;
}

ul a {
  text-decoration: none;
  color: #fff;
}
.policy-inner-case {
  gap: 16px;
  width: max-content;
}
.policy-inner-case li {
  width: max-content;
}
hr{
  border: unset;
  height: 0.5px;
  background-color: #fff;
}
.hr-thick {
  width: 73%;
}

@media screen and (max-width: 1200px) {
  .footer-outer-case {
    width: 95%;
    display: flex;
    flex-direction: column-reverse;
    gap: 32px;
    align-items: center;
  }
  .footer-inner-case{
    gap: 30px;
  }
  .footer-link-corner {
    flex-direction: column;
    width: max-content;
    margin: 0 50px;
    box-sizing: border-box;
  }
  .footer-page-link a {
    font-size: 20px;
  }
  .trim-case-policy,
  .trim-case-link {
    transform: rotate(90deg);
    transform-origin: 0 50%;
    top: 0px;
    left: -40px;
    position: absolute;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  .trim-case-link {
    width: 397px;
  }
  .trim-case-policy {
    width: 212px;
  }
}

</style>