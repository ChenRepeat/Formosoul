<script setup>
  import siteLogo from '@/assets/logo_white_L.png'; 
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  

</script>

<template>
  <main class="footer-case">
    <section class="footer-outer-case dp-flex">
      <div class="footer-icons-case dp-flex-col">
          <img :src="siteLogo" alt="Logo" class="logo-style">
          <div class="footer-social-link dp-flex">
            <a href=""><font-awesome-icon icon="fa-brands fa-github" class="footer-socialIcons" /></a>
            <a href=""><font-awesome-icon icon="fa-brands fa-instagram" class="footer-socialIcons" /></a>
            <a href=""><font-awesome-icon icon="fa-brands fa-square-facebook" class="footer-socialIcons" /></a>
            <a href=""><font-awesome-icon icon="fa-brands fa-x-twitter"  class="footer-socialIcons" /></a>
            
          </div>
      </div>

      <div class="footer-link-corner dp-flex">
        <div class="footer-link-case">
          <div class="trim-case-link dp-flex-col">
            <hr>
            <hr class="hr-thick">
          </div>
          <div class="footer-inner-case dp-flex">
            <ul class="footer-page-link dp-flex-col">
              <li><a href="/"><h5>Home</h5></a></li>
              <li><a href="/news"><h5>News</h5></a></li>
              <li><a href="/annualevent"><h5>Annual Event</h5></a></li>
              <li><a href="/professorsintroduction"><h5>Professors</h5></a></li>
              <li><a href="/survivalguide"><h5> Rules</h5></a></li>
              <li><a href="/shop"><h5>Diagon Alley</h5></a></li>
            </ul>
            <ul class="footer-classes-link dp-flex-col">
              <li class="footer-class-link-title"><a href="/classes"><h5>Classes</h5></a></li>
              <li><a href=""><p>Motorcycle</p></a></li>
              <li><a href=""><p>Endemic Species</p></a></li>
              <li><a href=""><p>History of Magic</p></a></li>
              <li><a href=""><p>Spells & Charms</p></a></li>
              <li><a href=""><p>Potions</p></a></li>
              <li><a href=""><p>Divination</p></a></li>
              <li></li>
            </ul>
          </div>
        </div>
        <div class="footer-policy-case">
          <div class="trim-case-policy dp-flex-col">
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

  .footer-link-corner a{
    width: 100%;
    display: block;
  }
  ul{
    list-style: none;
  }
  hr{
    margin: 0;
  }
  .footer-icons-case{
    height: auto;
    justify-content: center;
    gap: 60px;
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
    height: 100%;
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
    gap: 8px;
    justify-content: center;
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
  .footer-link-case{
    width: auto;
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
  .trim-case-policy,.trim-case-link{
    gap:8px;
    margin-bottom: 40px;
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
      margin: 0 50px;
      box-sizing: border-box;
    }
    .footer-page-link a{
      font-size: 20px;
    }
    .trim-case-policy,.trim-case-link{
      transform: rotate(90deg);
      transform-origin: 0 50%;
      top: -8px;left: -12px;
      position: absolute;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .trim-case-link{
      width: 241px;
    }
    .trim-case-policy{
      width: 80px;
    }
  }
</style>