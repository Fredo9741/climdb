<template>
  <div class="inline-block">
    <Button 
      size="sm" 
      variant="ghost" 
      @click="showConfirmation = true"
      :disabled="disabled"
      class="text-red-600 hover:text-red-700"
    >
      <Icon name="Trash2" class="h-4 w-4" />
    </Button>

    <!-- Modal de confirmation -->
    <div v-if="showConfirmation" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-full max-w-md mx-4 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mt-2">{{ title }}</h3>
          <div class="mt-2 px-4 py-3">
            <p class="text-sm text-gray-500 mb-3">
              {{ message }}
            </p>
            <div v-if="equipmentName" class="bg-gray-50 p-3 rounded-md border">
              <p class="text-sm font-medium text-gray-700 mb-1">Équipement à supprimer :</p>
              <p class="text-sm text-gray-900 break-words">{{ equipmentName }}</p>
            </div>
            <p class="text-xs text-red-600 mt-3 font-medium">
              Cette action est irréversible.
            </p>
          </div>
          <div class="flex justify-center space-x-4 mt-4">
            <button
              @click="showConfirmation = false"
              class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300"
            >
              Annuler
            </button>
            <button
              @click="confirmDelete"
              :disabled="processing"
              class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-50"
            >
              {{ processing ? 'Suppression...' : 'Supprimer' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Button from '@/components/ui/button/Button.vue'
import Icon from '@/components/Icon.vue'

interface Props {
  route: string
  title?: string
  message?: string
  disabled?: boolean
  equipmentName?: string
}

const props = withDefaults(defineProps<Props>(), {
  title: 'Confirmer la suppression',
  message: 'Êtes-vous sûr de vouloir supprimer cet élément ?',
  disabled: false,
  equipmentName: ''
})

const showConfirmation = ref(false)
const processing = ref(false)

const confirmDelete = () => {
  processing.value = true
  router.delete(props.route, {
    onSuccess: () => {
      showConfirmation.value = false
      processing.value = false
    },
    onError: () => {
      processing.value = false
    }
  })
}
</script> 