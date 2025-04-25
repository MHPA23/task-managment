<script setup>
import MainMenu from '@/Components/MainMenu.vue'

defineProps({
  title: String
})
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <MainMenu />
    
    <div class="py-8">
      <slot />
    </div>
  </div>
</template> 