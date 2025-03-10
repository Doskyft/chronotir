<template>
<div class="flex flex-col items-start gap-4 m-6">
  <label>
    Code de Synchronisation
    <input
        type="text"
        v-model="syncId"
        class="border rounded"
    />
  </label>

  <label>
    Temps de préparation
    <input
        type="number"
        v-model="preparationTime"
        class="border rounded"
    />
  </label>

  <label>
    Temps de tir
    <input
        type="number"
        v-model="shotTime"
        class="border rounded"
    />
  </label>

  <label>
    Temps de fin (orange)
    <input
        type="number"
        v-model="warningTime"
        class="border rounded"
    />
  </label>

  <div class="flex gap-3">
    <button
        class="border rounded px-2 py-1 cursor-pointer"
        @click="start"
    >
      Start
    </button>

    <button
        class="border rounded px-2 py-1 cursor-pointer"
        @click="stop"
    >
      Stop
    </button>

    <button
        class="border rounded px-2 py-1 cursor-pointer"
        @click="danger"
    >
      Alert
    </button>
  </div>
</div>
</template>

<script setup lang="ts">
const syncId: Ref<string | null> = ref('caen')
const preparationTime: Ref<number | null> = ref(10)
const shotTime: Ref<number | null> = ref(120)
const warningTime: Ref<number | null> = ref(30)

const API_URL = 'https:/chronotir.archers-caen.fr/api/'

function callApi(action: string, body = {}) {
  if (syncId.value === null) {
    return
  }

  body = {
    ...body,
    'syncId': syncId.value,
  }

  fetch('https:/localhost:4433/' + action, {
    method: 'POST',
    headers:{
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams(body)
  })
}

function start() {
  callApi('start', {
    preparationTime: preparationTime.value,
    shotTime: shotTime.value,
    warningTime: warningTime.value,
  })

  alert('Ordre envoyé')
}

function stop() {
  callApi(`stop`)

  alert('Ordre envoyé')
}

function danger() {
  callApi('alert')

  alert('Ordre envoyé')
}
</script>
