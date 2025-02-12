<template>
  <div v-if="syncId">
    <div
      class="min-h-screen min-w-screen flex justify-center items-center text-6xl"
      :class="{
        'bg-purple-100': state === 'waiting',
        'bg-red-500 text-white': state === 'preparation' || state === 'end',
        'bg-green-500 text-white': state === 'start' && time > warningTime,
        'bg-orange-500 text-white': state === 'start' && time <= warningTime,
      }"
    >
      <div
          v-if="state === 'waiting'"
      >
        🎯
      </div>

      <div v-else-if="state === 'preparation' || state === 'start'">
        {{ time }}
      </div>

      <div v-else>
        STOP
      </div>
    </div>
  </div>
  <div
    v-else
    class="m-4 flex flex-col items-start gap-3"
  >
    <label>
      Code de Synchronisation
      <input
          type="text"
          v-model="syncIdModel"
          class="border rounded"
      />
    </label>

    <button
      class="rounded border px-2 py-1 cursor-pointer"
      @click="setSyncId"
    >
      Synchroniser
    </button>
  </div>
</template>

<script setup lang="ts">
const DEFAULT_SHOT_TIME = 120
const DEFAULT_WARNING_TIME = 30
const DEFAULT_PREPARATION_TIME = 10

const syncIdModel: Ref<string | null> = ref(null)
const syncId: Ref<string | null> = ref(null)

const state: Ref<'waiting' | 'start' | 'preparation' | 'end'> = ref('waiting')

const preparationTime: Ref<number> = ref(DEFAULT_PREPARATION_TIME)
const shotTime: Ref<number> = ref(DEFAULT_SHOT_TIME)
const warningTime: Ref<number> = ref(DEFAULT_WARNING_TIME)
const time: Ref<number> = ref(0)

const intervalId: Ref<number | null> = ref(null)

function start() {
  state.value = 'preparation'
  time.value = preparationTime.value

  // @ts-ignore
  intervalId.value = setInterval(() => {
    time.value = time.value - 1

    if (state.value === 'preparation') {
      if (time.value === 0) {
        state.value = 'start'
        time.value = shotTime.value
      }
    } else if (state.value === 'start') {
      if (time.value === 0) {
        state.value = 'end'

        if (intervalId.value) {
          clearInterval(intervalId.value)
        }
      }
    }
  }, 1000)
}

function stop() {
  state.value = 'end'
  time.value = 0

  if (intervalId.value) {
    clearInterval(intervalId.value)
  }
}

function alert() {
  state.value = 'end'
  time.value = 0

  if (intervalId.value) {
    clearInterval(intervalId.value)
  }
}

function setSyncId() {
  syncId.value = syncIdModel.value

  if (!syncId.value) {
    return
  }

  const url = new URL('https://localhost:4433/.well-known/mercure')
  url.searchParams.append('topic', syncId.value)

  const eventSource = new EventSource(url);

  eventSource.onmessage = (e) => {
    console.log(e)

    const data: string = e.data

    if (data === 'start') {
      start()
    } else if (data === 'stop') {
      stop()
    } else if (data === 'alert') {
      alert()
    }
  };
}
</script>