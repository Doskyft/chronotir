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
        @click="alert"
    >
      Alert
    </button>
  </div>
</div>
</template>

<script setup lang="ts">
const syncId: Ref<string | null> = ref(null)

const API_URL = 'https:/localhost:4433/'

function callApi(action: string) {
  if (syncId.value === null) {
    return
  }

  fetch(API_URL + action, {
    method: 'POST',
    headers:{
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams({
      'syncId': syncId.value,
    })
  })
}

function start() {
  callApi('start')
}

function stop() {
  callApi('stop')
}

function alert() {
  callApi('alert')
}
</script>