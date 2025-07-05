<template>
  <AppLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Modèles de Relevés
          </h2>
          <p class="mt-1 text-sm text-gray-600">
            Gérez les modèles de relevés de mesures pour vos équipements
          </p>
        </div>
        <div class="flex items-center space-x-2">
          <Button 
            @click="$inertia.visit(route('modeles-releves.create'))"
            variant="default"
            class="flex items-center gap-2"
          >
            <Icon name="Plus" class="w-4 h-4" />
            Nouveau Modèle
          </Button>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
          <div class="p-6">
            <!-- Tableau des modèles -->
            <div class="overflow-x-auto">
              <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3">Nom</th>
                    <th scope="col" class="px-6 py-3">Description</th>
                    <th scope="col" class="px-6 py-3">Éléments</th>
                    <th scope="col" class="px-6 py-3">Créé le</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr 
                    v-for="modele in modeles" 
                    :key="modele.id"
                    class="bg-white border-b hover:bg-gray-50"
                  >
                    <td class="px-6 py-4 font-medium text-gray-900">
                      {{ modele.nom }}
                    </td>
                    <td class="px-6 py-4">
                      <div class="max-w-xs truncate">
                        {{ modele.description || 'Aucune description' }}
                      </div>
                    </td>
                    <td class="px-6 py-4">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ modele.elements_modele_releve_count }} éléments
                      </span>
                    </td>
                    <td class="px-6 py-4">
                      {{ formatDate(modele.created_at) }}
                    </td>
                    <td class="px-6 py-4">
                      <div class="flex items-center space-x-2">
                        <Button 
                          @click="$inertia.visit(route('modeles-releves.show', {modeles_relefe: modele.id}))"
                          variant="outline"
                          size="sm"
                          class="text-blue-600 hover:text-blue-800"
                        >
                          <Icon name="Eye" class="w-4 h-4" />
                        </Button>
                        <Button 
                          @click="$inertia.visit(route('modeles-releves.edit', {modeles_relefe: modele.id}))"
                          variant="outline"
                          size="sm"
                          class="text-green-600 hover:text-green-800"
                        >
                          <Icon name="Edit" class="w-4 h-4" />
                        </Button>
                        <Button 
                          @click="confirmDelete(modele)"
                          variant="outline"
                          size="sm"
                          class="text-red-600 hover:text-red-800"
                        >
                          <Icon name="Trash2" class="w-4 h-4" />
                        </Button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Message si aucun modèle -->
            <div v-if="modeles.length === 0" class="py-12 text-center">
              <Icon name="FileText" class="w-12 h-12 mx-auto text-gray-400" />
              <h3 class="mt-2 text-sm font-medium text-gray-900">
                Aucun modèle de relevé
              </h3>
              <p class="mt-1 text-sm text-gray-500">
                Commencez par créer votre premier modèle de relevé.
              </p>
              <div class="mt-6">
                <Button 
                  @click="$inertia.visit(route('modeles-releves.create'))"
                  variant="default"
                  class="flex items-center gap-2"
                >
                  <Icon name="Plus" class="w-4 h-4" />
                  Nouveau Modèle
                </Button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <Dialog :open="showDeleteModal" @update:open="showDeleteModal = $event">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Confirmer la suppression</DialogTitle>
          <DialogDescription>
            Êtes-vous sûr de vouloir supprimer le modèle de relevé "{{ modeleToDelete?.nom }}" ?
            Cette action est irréversible.
          </DialogDescription>
        </DialogHeader>
        <DialogFooter>
          <Button @click="showDeleteModal = false" variant="outline">
            Annuler
          </Button>
          <Button @click="deleteModele" variant="destructive">
            Supprimer
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Button from '@/components/ui/button/Button.vue'
import Icon from '@/components/Icon.vue'
import Dialog from '@/components/ui/dialog/Dialog.vue'
import DialogContent from '@/components/ui/dialog/DialogContent.vue'
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue'
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue'
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue'
import DialogFooter from '@/components/ui/dialog/DialogFooter.vue'

const props = defineProps({
  modeles: {
    type: Array,
    required: true
  }
})

const showDeleteModal = ref(false)
const modeleToDelete = ref(null)

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const confirmDelete = (modele) => {
  modeleToDelete.value = modele
  showDeleteModal.value = true
}

const deleteModele = () => {
  if (modeleToDelete.value) {
    router.delete(route('modeles-releves.destroy', {modeles_relefe: modeleToDelete.value.id}), {
      onSuccess: () => {
        showDeleteModal.value = false
        modeleToDelete.value = null
      }
    })
  }
}
</script> 