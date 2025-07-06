<template>
  <AppLayout title="Détails du technicien">
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-900">
            Détails du technicien
          </h2>
          <p class="mt-1 text-sm text-gray-600">
            Informations complètes et activités du technicien
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
          <Button
            @click="$inertia.visit(route('admin.techniciens.edit', technicien.id))"
          >
            <Icon name="edit" class="mr-2 h-4 w-4" />
            Modifier
          </Button>
        </div>
      </div>
    </template>

    <div class="space-y-6">
      <!-- Informations générales -->
      <Card>
        <CardHeader>
          <CardTitle>Informations générales</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <Label>Nom complet</Label>
              <p class="text-sm font-medium">{{ technicien.name }}</p>
            </div>
            <div>
              <Label>Email</Label>
              <p class="text-sm font-medium">{{ technicien.email }}</p>
            </div>
            <div>
              <Label>Téléphone</Label>
              <p class="text-sm font-medium">{{ technicien.telephone || 'Non renseigné' }}</p>
            </div>
            <div>
              <Label>Qualification</Label>
              <p class="text-sm font-medium">{{ technicien.qualification || 'Non renseignée' }}</p>
            </div>
            <div>
              <Label>Date d'inscription</Label>
              <p class="text-sm font-medium">{{ formatDate(technicien.created_at) }}</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Statistiques -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <Card>
          <CardContent class="p-4">
            <div class="flex items-center">
              <div class="p-2 bg-blue-100 rounded-lg">
                <Icon name="wrench" class="h-6 w-6 text-blue-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Interventions</p>
                <p class="text-2xl font-bold text-gray-900">{{ technicien.stats.interventions_count }}</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent class="p-4">
            <div class="flex items-center">
              <div class="p-2 bg-green-100 rounded-lg">
                <Icon name="certificate" class="h-6 w-6 text-green-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Habilitations</p>
                <p class="text-2xl font-bold text-gray-900">{{ technicien.stats.habilitations_count }}</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent class="p-4">
            <div class="flex items-center">
              <div class="p-2 bg-red-100 rounded-lg">
                <Icon name="alert-triangle" class="h-6 w-6 text-red-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Expirées</p>
                <p class="text-2xl font-bold text-gray-900">{{ technicien.stats.habilitations_expirees }}</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent class="p-4">
            <div class="flex items-center">
              <div class="p-2 bg-yellow-100 rounded-lg">
                <Icon name="gas-pump" class="h-6 w-6 text-yellow-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Mouvements gaz</p>
                <p class="text-2xl font-bold text-gray-900">{{ technicien.stats.mouvements_gaz_count }}</p>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      <!-- Habilitations -->
      <Card>
        <CardHeader>
          <CardTitle>Habilitations</CardTitle>
        </CardHeader>
        <CardContent>
          <div v-if="technicien.habilitations.length === 0" class="text-center py-8">
            <Icon name="certificate" class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune habilitation</h3>
            <p class="mt-1 text-sm text-gray-500">
              Ce technicien n'a pas encore d'habilitations enregistrées.
            </p>
          </div>
          <div v-else class="space-y-4">
            <div
              v-for="habilitation in technicien.habilitations"
              :key="habilitation.id"
              class="border rounded-lg p-4"
              :class="{
                'border-red-200 bg-red-50': habilitation.expired,
                'border-yellow-200 bg-yellow-50': habilitation.expires_soon && !habilitation.expired,
                'border-green-200 bg-green-50': !habilitation.expired && !habilitation.expires_soon
              }"
            >
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="font-medium text-gray-900">{{ habilitation.habilitation }}</h4>
                  <p class="text-sm text-gray-600">
                    Certificat: {{ habilitation.numero_certificat || 'Non renseigné' }}
                  </p>
                  <p class="text-sm text-gray-600">
                    Obtenue le: {{ formatDate(habilitation.date_obtention) }}
                  </p>
                  <p class="text-sm text-gray-600">
                    Expire le: {{ formatDate(habilitation.date_echeance) }}
                  </p>
                </div>
                <div class="flex items-center">
                  <span
                    v-if="habilitation.expired"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"
                  >
                    <Icon name="alert-triangle" class="mr-1 h-3 w-3" />
                    Expirée
                  </span>
                  <span
                    v-else-if="habilitation.expires_soon"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
                  >
                    <Icon name="clock" class="mr-1 h-3 w-3" />
                    Expire bientôt
                  </span>
                  <span
                    v-else
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                  >
                    <Icon name="check" class="mr-1 h-3 w-3" />
                    Valide
                  </span>
                </div>
              </div>
              <div v-if="habilitation.commentaires" class="mt-2">
                <p class="text-sm text-gray-600">{{ habilitation.commentaires }}</p>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Interventions récentes -->
      <Card>
        <CardHeader>
          <CardTitle>Interventions récentes</CardTitle>
        </CardHeader>
        <CardContent>
          <div v-if="technicien.interventions.length === 0" class="text-center py-8">
            <Icon name="wrench" class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune intervention</h3>
            <p class="mt-1 text-sm text-gray-500">
              Ce technicien n'a pas encore effectué d'interventions.
            </p>
          </div>
          <div v-else class="space-y-4">
            <div
              v-for="intervention in technicien.interventions"
              :key="intervention.id"
              class="border rounded-lg p-4"
            >
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="font-medium text-gray-900">
                    Intervention #{{ intervention.id }}
                  </h4>
                  <p class="text-sm text-gray-600">
                    Équipement: {{ intervention.equipement }}
                  </p>
                  <p class="text-sm text-gray-600">
                    Site: {{ intervention.site }}
                  </p>
                  <p class="text-sm text-gray-600">
                    Planifiée le: {{ formatDate(intervention.date_planifiee) }}
                  </p>
                </div>
                <div class="flex items-center">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="{
                      'bg-blue-100 text-blue-800': intervention.statut === 'planifiee',
                      'bg-yellow-100 text-yellow-800': intervention.statut === 'en_cours',
                      'bg-green-100 text-green-800': intervention.statut === 'terminee',
                      'bg-red-100 text-red-800': intervention.statut === 'annulee'
                    }"
                  >
                    {{ formatStatut(intervention.statut) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Affectations véhicules -->
      <Card>
        <CardHeader>
          <CardTitle>Véhicules affectés</CardTitle>
        </CardHeader>
        <CardContent>
          <div v-if="technicien.affectations_vehicules.length === 0" class="text-center py-8">
            <Icon name="car" class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun véhicule affecté</h3>
            <p class="mt-1 text-sm text-gray-500">
              Ce technicien n'a pas encore de véhicule affecté.
            </p>
          </div>
          <div v-else class="space-y-4">
            <div
              v-for="affectation in technicien.affectations_vehicules"
              :key="affectation.id"
              class="border rounded-lg p-4"
              :class="{
                'border-green-200 bg-green-50': affectation.active,
                'border-gray-200 bg-gray-50': !affectation.active
              }"
            >
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="font-medium text-gray-900">{{ affectation.vehicule }}</h4>
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
                <div class="flex items-center">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="{
                      'bg-green-100 text-green-800': affectation.active,
                      'bg-gray-100 text-gray-800': !affectation.active
                    }"
                  >
                    <Icon name="check" v-if="affectation.active" class="mr-1 h-3 w-3" />
                    <Icon name="clock" v-else class="mr-1 h-3 w-3" />
                    {{ affectation.active ? 'Actif' : 'Terminé' }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import Icon from '@/components/Icon.vue'

interface Technicien {
  id: number
  name: string
  email: string
  telephone: string | null
  qualification: string | null
  created_at: string
  habilitations: Array<{
    id: number
    habilitation: string
    date_obtention: string
    date_echeance: string
    numero_certificat: string | null
    commentaires: string | null
    expired: boolean
    expires_soon: boolean
  }>
  interventions: Array<{
    id: number
    date_planifiee: string
    statut: string
    equipement: string
    site: string
  }>
  affectations_vehicules: Array<{
    id: number
    vehicule: string
    immatriculation: string
    date_debut: string
    date_fin: string | null
    motif: string | null
    active: boolean
  }>
  stats: {
    interventions_count: number
    habilitations_count: number
    habilitations_expirees: number
    mouvements_gaz_count: number
  }
}

interface Props {
  technicien: Technicien
}

const props = defineProps<Props>()

const formatDate = (date: string) => {
  if (!date) return 'Non renseigné'
  return new Date(date).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const formatStatut = (statut: string) => {
  const statuts = {
    planifiee: 'Planifiée',
    en_cours: 'En cours',
    terminee: 'Terminée',
    annulee: 'Annulée'
  }
  return statuts[statut as keyof typeof statuts] || statut
}
</script> 