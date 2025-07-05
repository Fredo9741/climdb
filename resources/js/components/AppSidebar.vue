<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { 
    LayoutGrid, 
    Users, 
    MapPin, 
    Settings, 
    AlertTriangle, 
    Wrench, 
    FileText, 
    Receipt,
    BookOpen, 
    Folder,
    Package,
    Car,
    Cylinder,
    ArrowUpDown
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

const page = usePage();

// Vérification des rôles avec protection
const isAdmin = computed(() => {
    const user = page.props.auth?.user;
    if (!user) return false;
    
    // Vérification basée sur l'email admin en priorité  
    const isAdminEmail = user.email === 'admin@climdb.fr' || user.email === 'admin@test.com' || user.email === 'admin@example.com';
    
    // Vérification des rôles si disponibles
    let hasAdminRole = false;
    if (user.roles) {
        if (Array.isArray(user.roles)) {
            hasAdminRole = user.roles.includes('admin');
        } else if (typeof user.roles === 'object') {
            hasAdminRole = Object.values(user.roles).includes('admin');
        }
    }
    
    return isAdminEmail || hasAdminRole;
});

const isTechnicien = computed(() => {
    const user = page.props.auth?.user;
    if (!user) return false;
    
    const roles = user.roles;
    
    if (Array.isArray(roles)) {
        return roles.includes('technicien');
    }
    
    if (roles && typeof roles === 'object') {
        return Object.values(roles).includes('technicien');
    }
    
    return false;
});

// Navigation principale avec gestion des permissions
const mainNavItems = computed((): NavItem[] => {
    const items: NavItem[] = [
        {
            title: 'Tableau de bord',
            href: '/dashboard',
            icon: LayoutGrid,
        },
    ];

    // Éléments disponibles pour tous les utilisateurs authentifiés
    items.push(
        {
            title: 'Pannes',
            href: '/pannes',
            icon: AlertTriangle,
        },
        {
            title: 'Interventions',
            href: '/interventions',
            icon: Wrench,
        }
    );

    // Éléments réservés aux admins
    if (isAdmin.value) {
        items.push(
            {
                title: 'Clients',
                href: '/clients',
                icon: Users,
            },
            {
                title: 'Sites',
                href: '/sites',
                icon: MapPin,
            },
            {
                title: 'Modèles',
                href: '/modeles',
                icon: Package,
            },
            {
                title: 'Modèles de Relevés',
                href: '/modeles-releves',
                icon: FileText,
            },
            {
                title: 'Équipements',
                href: '/equipements',
                icon: Settings,
            },
            {
                title: 'Véhicules',
                href: '/vehicules',
                icon: Car,
            },
            {
                title: 'Bouteilles de Gaz',
                href: '/bouteilles-gaz',
                icon: Cylinder,
            },
            {
                title: 'Mouvements de Gaz',
                href: '/mouvements-gaz',
                icon: ArrowUpDown,
            },
            {
                title: 'Devis',
                href: '/devis',
                icon: FileText,
            },
            {
                title: 'Factures',
                href: '/factures',
                icon: Receipt,
            }
        );
    }

    return items;
});

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
