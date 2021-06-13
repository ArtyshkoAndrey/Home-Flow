<template>
  <div class="row w-100 justify-content-center align-items-center">
    <div class="col-md-4">
      <card class="text-center">
        <h3 class="mb-4">
          {{ $t('page_not_found') }}
        </h3>


        <router-link :to="{ name: 'welcome' }" class="btn btn-outline-primary rounded">
          {{ $t('go_home') }}
        </router-link>
      </card>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotFound'
}
</script>
