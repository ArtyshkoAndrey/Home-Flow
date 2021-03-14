<template>
  <card :title="$t('all_modules')">
    <div class="row">
      <div class="col-md-6 col-12">
        <home-module-info title="Температура"
                          bg-color="bg-secondary"
                          info="+ 27"
                          type="C"
                          ico="temperature-high"
                          last-update="Последнее обновление 5 минут назад"
        />
      </div>
      <div class="col-md-6 col-12">
        <home-module-info title="Сигнализация"
                          bg-color="bg-secondary"
                          info="Выкл"
                          ico="shield-alt"
                          last-update="Последнее обновление 30 минут назад"
        />
      </div>
    </div>
  </card>
</template>

<script>
export default {
  name: 'Index'
}
</script>

<style scoped>

</style>
