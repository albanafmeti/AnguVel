<template>
  <div>
    <!-- Hero Section-->
    <section style="background: url(/static/img/hero.jpg); background-size: cover; background-position: center center"
             class="hero">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <h1>TeRejat.al - Njihuni me te rejat me te fundit nga vendi dhe bota</h1><a href="#" class="hero-link">Zbulo me shume</a>
          </div>
        </div>
        <a href=".featured-posts" class="continue link-scroll"><i class="fa fa-long-arrow-down"></i> Zbrit Poshte</a>
      </div>
    </section>

    <!-- Intro Section-->
    <section class="intro">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <h2 class="h3">Kliko dhe Informohu</h2>
            <p class="text-big">Njihuni me informacionet me te fundit nga vendi dhe bota duke naviguar ne artikujt e
              portalit tone. Fokusohemi kryesisht ne <u>showbiz</u>, <u>lifestyle</u> dhe <u>sport</u>. Navigim te kendshem!
            </p>
          </div>
        </div>
      </div>
    </section>

    <app-featured-posts></app-featured-posts>

    <app-latest-posts-home></app-latest-posts-home>

    <app-newsletter></app-newsletter>
  </div>
</template>

<script>
  import FeaturedPosts from './FeaturedPosts';
  import LatestPosts from './LatestPosts';
  import Newsletter from './Newsletter';

  export default {
    name: 'Home',
    components: {
      'app-featured-posts': FeaturedPosts,
      'app-latest-posts-home': LatestPosts,
      'app-newsletter': Newsletter,
    },
  };
</script>
