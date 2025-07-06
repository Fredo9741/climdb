<template>
  <AppLayout title="Modifier le technicien">
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-900">
            Modifier le technicien
          </h2>
          <p class="mt-1 text-sm text-gray-600">
            Modifier les informations et habilitations du technicien
          </p>
        </div>
        <div class="flex gap-2">
          <Button
            variant="outline"
            @click="$inertia.visit(route('admin.techniciens.index'))"
          >
            <Icon name="arrow-left" class="mr-2 h-4 w-4" />
            Retour
          </Button>
        </div>
      </div>
    </template>

    <form @submit.prevent="submit" class="space-y-6">
      <!-- Informations générales -->
      <Card>
        <CardHeader>
          <CardTitle>Informations générales</CardTitle>
        </CardHeader>
        <CardContent class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <Label for="name">Nom complet *</Label>
              <Input
                id="name"
                v-model="form.name"
                type="text"
                required
                :error="form.errors.name"
              />
            </div>
            <div>
              <Label for="email">Email *</Label>
              <Input
                id="email"
                v-model="form.email"
                type="email"
                required
                :error="form.errors.email"
              />
            </div>
            <div>
              <Label for="telephone">Téléphone</Label>
              <Input
                id="telephone"
                v-model="form.telephone"
                type="tel"
                :error="form.errors.telephone"
              />
            </div>
            <div>
              <Label for="qualification">Qualification</Label>
              <Input
                id="qualification"
                v-model="form.qualification"
                type="text"
                :error="form.errors.qualification"
              />
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Habilitations existantes -->
      <Card v-if="technicien.habilitations.length > 0">
        <CardHeader>
          <CardTitle>Habilitations actuelles</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-4">
            <div
              v-for="habilitation in technicien.habilitations"
              :key="habilitation.id"
              class="border rounded-lg p-4"
            >
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="font-medium text-gray-900">{{ habilitation.habilitation_nom }}</h4>
                  <p class="text-sm text-gray-600">
                    Obtenue le: {{ formatDate(habilitation.date_obtention) }}
                  </p>
                  <p class="text-sm text-gray-600">
                    Expire le: {{ formatDate(habilitation.date_echeance) }}
                  </p>
                  <p v-if="habilitation.numero_certificat" class="text-sm text-gray-600">
                    Certificat: {{ habilitation.numero_certificat }}
                  </p>
                </div>
                <Button
                  type="button"
                  variant="outline"
                  size="sm"
                  @click="removeHabilitation(habilitation.id)"
                >
                  <Icon name="trash" class="h-4 w-4" />
                </Button>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Ajouter une nouvelle habilitation -->
      <Card>
        <CardHeader>
          <CardTitle>Ajouter une habilitation</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <Label for="habilitation_id">Type d'habilitation *</Label>
                <select
                  id="habilitation_id"
                  v-model="newHabilitation.habilitation_id"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  required
                >
                  <option value="">Sélectionner une habilitation</option>
                  <option
                    v-for="habilitation in habilitations"
                    :key="habilitation.id"
                    :value="habilitation.id"
                  >
                    {{ habilitation.nom }}
                  </option>
                </select>
              </div>
              <div>
                <Label for="date_obtention">Date d'obtention *</Label>
                <Input
                  id="date_obtention"
                  v-model="newHabilitation.date_obtention"
                  type="date"
                  required
                />
              </div>
              <div>
                <Label for="numero_certificat">Numéro de certificat</Label>
                <Input
                  id="numero_certificat"
                  v-model="newHabilitation.numero_certificat"
                  type="text"
                />
              </div>
              <div>
                <Label for="commentaires">Commentaires</Label>
                <Input
                  id="commentaires"
                  v-model="newHabilitation.commentaires"
                  type="text"
                />
              </div>
            </div>
            <Button
              type="button"
              variant="outline"
              @click="addHabilitation"
              :disabled="!canAddHabilitation"
            >
              <Icon name="plus" class="mr-2 h-4 w-4" />
              Ajouter l'habilitation
            </Button>
          </div>
        </CardContent>
      </Card>

      <!-- Affectations véhicules existantes -->
      <Card v-if="technicien.affectations_vehicules.length > 0">
        <CardHeader>
          <CardTitle>Affectations véhicules actuelles</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-4">
            <div
              v-for="affectation in technicien.affectations_vehicules"
              :key="affectation.id"
              class="border rounded-lg p-4"
            >
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="font-medium text-gray-900">{{ affectation.vehicule_nom }}</h4>
                  <p class="text-sm text-gray-600">
                    Immatriculation: {{ affectation.immatriculation }}
                  </p>
                  <p class="text-sm text-gray-600">
                    Du: {{ formatDate(affectation.date_debut) }}
                  </p>
                  <p class="text-sm text-gray-600">
                    Au: {{ affectation.date_fin ? formatDate(affectation.date_fin) : 'En cours' }}
                  </p>
                  <p v-if="affectation.motif" class="text-sm text-gray-600">
                    Motif: {{ affectation.motif }}
                  </p>
                </div>
                <Button
                  type="button"
                  variant="outline"
                  size="sm"
                  @click="removeAffectationVehicule(affectation.id)"
                >
                  <Icon name="trash" class="h-4 w-4" />
                </Button>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Ajouter une nouvelle affectation véhicule -->
      <Card>
        <CardHeader>
          <CardTitle>Affecter un véhicule</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <Label for="vehicule_id">Véhicule *</Label>
                <select
                  id="vehicule_id"
                  v-model="newAffectationVehicule.vehicule_id"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  required
                >
                  <option value="">Sélectionner un véhicule</option>
                  <option
                    v-for="vehicule in vehicules"
                    :key="vehicule.id"
                    :value="vehicule.id"
                  >
                    {{ vehicule.marque }} {{ vehicule.modele }} - {{ vehicule.immatriculation }}
                  </option>
                </select>
              </div>
              <div>
                <Label for="date_debut_vehicule">Date de début *</Label>
                <Input
                  id="date_debut_vehicule"
                  v-model="newAffectationVehicule.date_debut"
                  type="date"
                  required
                />
              </div>
              <div>
                <Label for="date_fin_vehicule">Date de fin</Label>
                <Input
                  id="date_fin_vehicule"
                  v-model="newAffectationVehicule.date_fin"
                  type="date"
                />
              </div>
              <div>
                <Label for="motif_vehicule">Motif</Label>
                <Input
                  id="motif_vehicule"
                  v-model="newAffectationVehicule.motif"
                  type="text"
                />
              </div>
            </div>
            <Button
              type="button"
              variant="outline"
              @click="addAffectationVehicule"
              :disabled="!canAddAffectationVehicule"
            >
              <Icon name="plus" class="mr-2 h-4 w-4" />
              Affecter le véhicule
            </Button>
          </div>
        </CardContent>
      </Card>

      <!-- Actions -->
      <div class="flex justify-end gap-4">
        <Button
          type="button"
          variant="outline"
          @click="$inertia.visit(route('admin.techniciens.index'))"
        >
          Annuler
        </Button>
        <Button type="submit" :disabled="form.processing">
          <Icon name="save" class="mr-2 h-4 w-4" />
          {{ form.processing ? 'Enregistrement...' : 'Enregistrer' }}
        </Button>
      </div>
    </form>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import Icon from '@/components/Icon.vue'

interface Habilitation {
  id: number
  nom: string
}

interface Vehicule {
  id: number
  marque: string
  modele: string
  immatriculation: string
}

interface TechnicienHabilitation {
  id: number
  habilitation_id: number
  habilitation_nom: string
  date_obtention: string
  date_echeance: string
  numero_certificat: string | null
  commentaires: string | null
}

interface AffectationVehicule {
  id: number
  vehicule_id: number
  vehicule_nom: string
  immatriculation: string
  date_debut: string
  date_fin: string | null
  motif: string | null
}

interface Technicien {
  id: number
  name: string
  email: string
  telephone: string | null
  qualification: string | null
  habilitations: TechnicienHabilitation[]
  affectations_vehicules: AffectationVehicule[]
}

interface Props {
  technicien: Technicien
  habilitations: Habilitation[]
  vehicules: Vehicule[]
}

const props = defineProps<Props>()

const form = useForm({
  name: props.technicien.name,
  email: props.technicien.email,
  telephone: props.technicien.telephone || '',
  qualification: props.technicien.qualification || '',
  habilitations: [] as Array<{
    habilitation_id: number
    date_obtention: string
    numero_certificat: string
    commentaires: string
  }>,
  affectations_vehicules: [] as Array<{
    vehicule_id: number
    date_debut: string
    date_fin: string
    motif: string
  }>
})

const newHabilitation = useForm({
  habilitation_id: '',
  date_obtention: '',
  numero_certificat: '',
  commentaires: ''
})

const newAffectationVehicule = useForm({
  vehicule_id: '',
  date_debut: '',
  date_fin: '',
  motif: ''
})

const canAddHabilitation = computed(() => {
  return newHabilitation.habilitation_id && newHabilitation.date_obtention
})

const canAddAffectationVehicule = computed(() => {
  return newAffectationVehicule.vehicule_id && newAffectationVehicule.date_debut
})

const addHabilitation = () => {
  if (!canAddHabilitation.value) return

  form.habilitations.push({
    habilitation_id: parseInt(newHabilitation.habilitation_id),
    date_obtention: newHabilitation.date_obtention,
    numero_certificat: newHabilitation.numero_certificat,
    commentaires: newHabilitation.commentaires
  })

  // Reset form
  newHabilitation.reset()
}

const removeHabilitation = (habilitationId: number) => {
  const index = form.habilitations.findIndex(h => h.habilitation_id === habilitationId)
  if (index > -1) {
    form.habilitations.splice(index, 1)
  }
}

const addAffectationVehicule = () => {
  if (!canAddAffectationVehicule.value) return

  form.affectations_vehicules.push({
    vehicule_id: parseInt(newAffectationVehicule.vehicule_id),
    date_debut: newAffectationVehicule.date_debut,
    date_fin: newAffectationVehicule.date_fin,
    motif: newAffectationVehicule.motif
  })

  // Reset form
  newAffectationVehicule.reset()
}

const removeAffectationVehicule = (affectationId: number) => {
  const index = form.affectations_vehicules.findIndex(a => a.vehicule_id === affectationId)
  if (index > -1) {
    form.affectations_vehicules.splice(index, 1)
  }
}

const submit = () => {
  form.put(route('admin.techniciens.update', props.technicien.id))
}

const formatDate = (date: string) => {
  if (!date) return 'Non renseigné'
  return new Date(date).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}
</script> 