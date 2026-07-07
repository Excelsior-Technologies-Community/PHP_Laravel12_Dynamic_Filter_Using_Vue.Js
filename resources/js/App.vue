<template>

<div class="min-h-screen bg-gray-100">

    <nav class="bg-white shadow">

        <div class="max-w-7xl mx-auto px-6 py-4">

            <h1 class="text-3xl font-bold">

                Laravel 12 Dynamic Product Filter

            </h1>

        </div>

    </nav>

    <div class="max-w-7xl mx-auto p-6">

        <Dashboard/>

        <ProductFilter/>

    </div>

</div>

</template>

<script setup>

import Dashboard from './components/Dashboard.vue'

import ProductFilter from './components/ProductFilter.vue'

</script>