<script setup>
  import siteLogo from '@/assets/logo_white_L.png'; 
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
</script>

<template>
  <main class="footer-case">
    <section class="footer-outer-case dp-flex">
      <div class="footer-icons-case dp-flex-col">
          <img :src="siteLogo" alt="Logo" class="logo-style">
          <div class="footer-social-link">
            <a href=""><font-awesome-icon icon="fa-brands fa-twitter" beat class="footer-socialIcons" /></a>
          </div>
      </div>

      <div class="footer-link-corner dp-flex">
        <div class="footer-link-case">
          <div class="trim-case-link">
            <hr>
            <hr class="hr-thick">
          </div>
          <div class="footer-inner-case dp-flex">
            <ul class="footer-page-link dp-flex-col">
              <li><a href="/">Home</a></li>
              <li><a href="/news">News</a></li>
              <li><a href="/annualevent">Annual Event</a></li>
              <li><a href="/professorsintroduction">Professors</a></li>
              <li><a href="/survivalguide">Survival Rules</a></li>
              <li><a href="/shop">Diagon Alley</a></li>
            </ul>
            <ul class="footer-classes-link dp-flex-col">
              <li class="footer-class-link-title"><a href="/classes">Classes</a></li>
              <li><a href="">Motorcycle</a></li>
              <li><a href="">Endemic Species</a></li>
              <li><a href="">History of Magic</a></li>
              <li><a href="">Spells & Charms</a></li>
              <li><a href="">Potions</a></li>
              <li><a href="">Divination</a></li>
              <li></li>
            </ul>
          </div>
        </div>
        <div class="footer-policy-case">
          <div class="trim-case-policy">
            <hr>
            <hr class="hr-thick">
          </div>
          <ul class="policy-inner-case dp-flex-col">
            <li><a href="/policy">Policy</a></li>
            <li><a href="">Overseas Purchase Payment</a></li>
            <li><a href="">Privacy Policy</a></li>
          </ul>
        </div>
      </div>
    </section>
    <div class="tibame-policy">本網站為緯育TibaMe_前端工程師班第98期學員專題成果作品，本平台僅供學習、展示之用。<br>若有抵觸有關著作權，或有第三人主張侵害智慧財產權等情事，均由學員負法律上責任，緯育公司概不負責。若有侵權疑慮，您可以私訊「<a href="">緯育TibaMe</a>」，後續會由專人協助處理。</div>
  </main>
</template>

<style scoped lang="scss">
  a{
    width: 100%;
    display: block;
  }
  ul{
    list-style: none;
  }
  .footer-icons-case{
    height: auto;
    justify-content: center;
  }
  .logo-style{
    align-self: center;
  }
  .footer-link-corner{
    gap: 60px;
  }
  .footer-case{
    background-color: #041426;
    width: 100%;
    padding-top: 60px;
    padding-bottom: 16px;
  }
  .footer-outer-case{
    gap: 60px;
    justify-content: center;
  }
  .footer-inner-case{
    justify-content: start;
    gap: 60px;
  }
  .footer-social-link{
    text-align: center;
  }
  .footer-socialIcons{
    font-size: 32px;
    color: $color-fsWhite;
    cursor: pointer;
  }
  .dp-flex-col{
    display: flex;
    flex-direction: column;
  }
  .footer-page-link{
    gap: 14px;
    padding: 0;
  }
  .footer-page-link a{
    font-size: 28px;
  }
  .footer-link-case{
    width: 461px;
    position: relative;
  }
  .footer-classes-link{
    gap: 16px;
    padding: 0;
  }
  .footer-class-link-title{
    margin-bottom: 8px;
    font-size: 28px;
  }
  .footer-policy-case{
    width: auto;
    position: relative;
  }
  .footer-policy-case ul{
    padding: 0;
  }
  .tibame-policy{
    text-align: center;
    font-size: 12px;
    color:#B0B0B0;
    margin-top: 60px;
  }
  ul a{
    text-decoration: none;
    color: #fff;

  }
  .policy-inner-case{
    gap: 16px;
    width: max-content;
    
  }
  .policy-inner-case li{
    width: max-content;
  }
  .hr-thick{
    width: 73%;
    margin-left: 0;
    transform: scaleY(0.5);
  }
  .tibame-policy a{
    display: inline;
    color:#B0B0B0;
  }
  @media screen and (max-width: 1200px){
    .footer-outer-case{
      display: flex;
      flex-direction: column-reverse;
      gap: 32px;
      align-items: center;
    }
    .footer-link-corner{
      flex-direction: column;
      width: max-content;
    }
    .footer-page-link a{
      font-size: 20px;
    }
    .trim-case-policy,.trim-case-link{
      transform: rotate(90deg);
      transform-origin: 0 50%;
      top: -12px;left: -12px;
      position: absolute;
    }
    .trim-case-link{
      width: 241px;
    }
    .trim-case-policy{
      width: 80px;
    }
  }
</style>