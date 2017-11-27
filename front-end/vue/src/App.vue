<template>
  <div id="app">
    <app-header></app-header>
    <router-view></router-view>
    <app-footer></app-footer>
  </div>
</template>

<script>
  import AppHeader from '@/components/AppHeader';
  import AppFooter from '@/components/AppFooter';

  export default {
    components: {
      'app-header': AppHeader,
      'app-footer': AppFooter,
    },
    name: 'app',
  };
</script>
