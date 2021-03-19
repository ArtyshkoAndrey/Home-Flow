<template>
  <card :title="$t('home_settings.module.title')">
    <div class="row">
      <form @submit.prevent="login" @keydown="form.onKeydown($event)">
        <!-- Email -->
        <div class="row mb-3">
          <label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
          <div class="col-md-7">
            <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control"
                   type="text" name="name">
            <has-error :form="form" field="name"/>
          </div>
        </div>

        <!-- Password -->
        <div class="row mb-3">
          <label class="col-md-3 col-form-label text-md-right">IP</label>
          <div class="col-md-7">
            <input v-model="form.ip" :class="{ 'is-invalid': form.errors.has('ip') }" class="form-control" type="text"
                   name="ip">
            <has-error :form="form" field="ip"/>
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-md-3 col-form-label text-md-right">Индекс <span class="small">(Google Home)</span></label>
          <div class="col-md-7">
            <input v-model="form.index"
                   :class="{ 'is-invalid': form.errors.has('index') }"
                   class="form-control"
                   type="text"
                   name="index"
            >
            <has-error :form="form" field="index"/>
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-md-3 col-form-label text-md-right">Частота обновления</label>
          <div class="col-md-7">
            <input v-model="form.update"
                   :class="{ 'is-invalid': form.errors.has('update') }"
                   class="form-control"
                   type="time"
                   name="update"
            >
            <has-error :form="form" field="update" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-md-3 col-form-label text-md-right">Тип</label>
          <div class="col-md-7">
            <select id="type" v-model="form.type"
                    name="type"
                    :class="{ 'is-invalid': form.errors.has('type') }"
                    class="form-control"
            >
              <option value="integer">
                Числовой
              </option>
              <option value="boolean">
                On/Off
              </option>
              <option value="stringr">
                Строка
              </option>
            </select>
            <has-error :form="form" field="type" />
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-7 offset-md-3 d-flex">
            <!-- Submit Button -->
            <v-button :loading="form.busy">
              {{ $t('save') }}
            </v-button>
          </div>
        </div>
      </form>
    </div>
  </card>
</template>

<script>
import Form from 'vform'

export default {
  name: 'Module',

  metaInfo () {
    return { title: 'Настройки модуля температуры' }
  },

  data: () => ({
    form: new Form({
      name: 'Температура',
      ip: '127.0.0.1:33001',
      index: 'temp-room',
      update: '',
      type: ''
    })
  })
}
</script>

<style scoped>

</style>
