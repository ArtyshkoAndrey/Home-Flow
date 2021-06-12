<template>
  <card :title="$t('index')">
    {{ $t('you_are_logged_in') }}
    <p class="mt-1">В дальнейшем, в данном разделе будет отображаться собранная статистика всех устроств, после их добавления в систему</p>
  </card>
</template>

<script>
export default {
  middleware: 'auth',

  metaInfo () {
    return { title: this.$t('index') }
  }
}
</script>
