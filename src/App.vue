<template>
  <v-app>
        <router-view />
    </v-app>
</template>

<script>
export default {
    name: 'App',
    watch: {
        $route: {
            immediate: true,
            handler(to) {
                document.title = to.meta.title || 'Laravel Exam';
            }
        },
    }
}
</script>
