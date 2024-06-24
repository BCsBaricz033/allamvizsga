<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import anime from 'animejs/lib/anime.es.js';

const isOpen = ref(false);

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const animatedText = ref(null);

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}

function animateText() {
    const textWrapper = animatedText.value;
    if (textWrapper) {
        textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

        anime.timeline({ loop: true })
            .add({
                targets: '.letter',
                translateY: [100, 0],
                opacity: [0, 1],
                easing: "easeOutExpo",
                duration: 1400,
                delay: (el, i) => 50 * i
            }).add({
                targets: '.letter',
                translateY: [0, -100],
                opacity: [1, 0],
                easing: "easeInExpo",
                duration: 1200,
                delay: (el, i) => 50 * i
            });
    }
}

onMounted(() => animateText());
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50 min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white sm:flex sm:justify-between sm:items-center sm:px-4 sm:py-3 relative">
            <div class="flex items-center justify-between px-4 py-3 sm:p-0">
                <div>
                    <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                </div>
                <div class="sm:hidden">
                    <button @click="isOpen = !isOpen" type="button"
                        class="block text-gray-500 hover:text-black focus:text-black focus:outline-none">
                        <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                            <path v-if="isOpen" fill-rule="evenodd"
                                d="M18.278 16.864a1 1 0 0 1-1.414 1.414l-4.829-4.828-4.828 4.828a1 1 0 0 1-1.414-1.414l4.828-4.829-4.828-4.828a1 1 0 0 1 1.414-1.414l4.829 4.828 4.828-4.828a1 1 0 1 1 1.414 1.414l-4.828 4.829 4.828 4.828z" />
                            <path v-if="!isOpen" fill-rule="evenodd"
                                d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z" />
                        </svg>
                    </button>
                </div>
            </div>
            <nav v-if="props.canLogin" :class="isOpen ? 'block' : 'hidden'"
                class=" z-20 absolute top-full left-0 w-full bg-white px-2 pt-2 pb-4 sm:static sm:flex sm:p-0 sm:justify-end">
                <Link v-if="$page.props.auth.user && $page.props.auth.user.role === 'admin'"
                    :href="route('admin.dashboard')"
                    class="block rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                Dashboard
                </Link>
                <Link v-else-if="$page.props.auth.user && $page.props.auth.user.role === 'user'"
                    :href="route('user.dashboard')"
                    class="block rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                Dashboard
                </Link>
                <Link v-else-if="$page.props.auth.user && $page.props.auth.user.role === 'doctor'"
                    :href="route('doctor.dashboard')"
                    class="block rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                Dashboard
                </Link>
                <Link v-else-if="$page.props.auth.user && $page.props.auth.user.role === 'assistant'"
                    :href="route('assistant.dashboard')"
                    class="block rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                Dashboard
                </Link>
                <template v-else>
                    <Link :href="route('login')"
                        class="block rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                    Log in
                    </Link>
                    <Link v-if="props.canRegister" :href="route('register')"
                        class="block rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                    Register
                    </Link>
                </template>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="flex-grow bg-cover bg-center bg-no-repeat h-[75vh]"
            style="background-image: url('/assets/background.jpg')">
            <div class="flex justify-start items-start h-full p-6">
                <div class="bg-white bg-opacity-70 p-4 rounded-lg">
                    <h1 ref="animatedText" class="text-animation__title text-6xl text-black">Welcome.</h1>
                    <p class="mt-2 text-black">Welcome to the doctor's office appointment page.</p>
                    <button v-if="props.canLogin && !$page.props.auth.user " class="mr-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"><Link :href="route('reserve_without_login')">
                    Reserve
                    </Link></button>

                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-16 bg-white text-center text-sm text-black dark:text-white/70 h-[5vh]">
            Created by Baricz Csaba
        </footer>
    </div>
</template>

<style scoped>
.text-animation {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.text-animation__title {
  font-size: 3rem;
  color: #000;
  overflow: hidden;
  white-space: nowrap;
}

.letter {
  display: inline-block;
  line-height: 1em;
}
</style>
