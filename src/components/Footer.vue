<script setup>
  import siteLogo from '@/assets/logo_white_L.png'; 
</script>

<template>
  <main class="footer-case">
    <section class="footer-inner-case dp-flex">
      <div class="footer-icons-case dp-flex-col">
          <img :src="siteLogo" alt="Logo" class="logo-style">
      </div>
      <div class="footer-link-case">
        <hr>
        <hr class="hr-thick">
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
          </ul>
        </div>
      </div>
      <div class="footer-policy-case">
        <hr>
        <hr class="hr-thick">
        <ul class="policy-inner-case dp-flex-col">
          <li><a href="/policy">Policy</a></li>
          <li><a href="">Overseas Purchase Payment</a></li>
          <li><a href="">Privacy Policy</a></li>
        </ul>
      </div>
    </section>
    <div class="tibame-policy">本網站為緯育TibaMe_前端工程師班第98期學員專題成果作品，本平台僅供學習、展示之用。<br>若有抵觸有關著作權，或有第三人主張侵害智慧財產權等情事，均由學員負法律上責任，緯育公司概不負責。若有侵權疑慮，您可以私訊「<a href="">緯育TibaMe</a>」，後續會由專人協助處理。</div>
  </main>
</template>

<style scoped>
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
  .footer-case{
    background-color: #041426;
    width: 1440px;
    padding-top: 60px;
    padding-bottom: 16px;
  }
  .footer-inner-case{
    gap: 60px;
    justify-content: center;
  }
  .dp-flex{
    display: flex;
  }
  .dp-flex-col{
    display: flex;
    flex-direction: column;
  }
  .footer-page-link{
    gap: 14px;
    padding: 0;;
  }
  .footer-page-link a{
    font-size: 28px;
  }
  .footer-link-case{
    width: 461px;
  }
  .footer-classes-link{
    gap: 16px;
    padding: 0;
  }
  .footer-class-link-title{
    margin-bottom: 8px;
    font-size: 28px;
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
  }
  .hr-thick{
    width: 73%;
    margin-left: 0;
    size: 0.5px;
  }
  .tibame-policy a{
    display: inline;
    color:#B0B0B0;
  }
</style>