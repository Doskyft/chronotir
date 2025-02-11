<template>
  <div>
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

    <button
      class="bg-white fixed bottom-1 right-1 rounded px-2 py-1 cursor-pointer"
      @click="start"
    >
      Start
    </button>
  </div>
</template>

<script setup lang="ts">
const DEFAULT_SHOT_TIME = 120
const DEFAULT_WARNING_TIME = 30
const DEFAULT_PREPARATION_TIME = 10

const state: Ref<'waiting' | 'start' | 'preparation' | 'end'> = ref('waiting')
const preparationTime: Ref<number> = ref(DEFAULT_PREPARATION_TIME)
const shotTime: Ref<number> = ref(DEFAULT_SHOT_TIME)
const warningTime: Ref<number> = ref(DEFAULT_WARNING_TIME)
const time: Ref<number> = ref(0)

function start() {
  state.value = 'preparation'
  time.value = preparationTime.value

  const timerId = setInterval(() => {
    time.value = time.value - 1

    if (state.value === 'preparation') {
      if (time.value === 0) {
        state.value = 'start'
        time.value = shotTime.value
      }
    } else if (state.value === 'start') {
      if (time.value === 0) {
        state.value = 'end'

        clearInterval(timerId)
      }
    }
  }, 1000)
}
</script>