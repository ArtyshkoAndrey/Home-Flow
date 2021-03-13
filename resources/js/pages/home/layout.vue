<template>
  <div class="row">
    <div class="col-md-3">
      <card title="Меню настроек" class="settings-card">
        <div class="list-group">
          <router-link v-for="tab in tabs"
                       :key="tab.route"
                       :to="{ name: tab.route }"
                       class="list-group-item list-group-item-action border-0"
                       active-class="active"
          >
            <fa :icon="tab.icon"
                fixed-width
            />
            {{ tab.name }}
          </router-link>
        </div>
      </card>
    </div>
    <div class="col-md-9">
      <transition name="fade" mode="out-in">
        <router-view />
      </transition>
    </div>
  </div>
</template>

<script>

export default {
  name: 'HomeLayout',
  middleware: 'auth',
  scrollToTop: false,
  computed: {
    tabs () {
      return [
        {
          icon: 'home',
          name: 'Главная',
          route: 'home.index'
        }
      ]
    }
  }
}
</script>

<style lang="scss">
  @import "resources/sass/settings";
</style>
