<template>
    <div class="mb-8">

        <h2 class="text-3xl font-bold mb-6">
            Dashboard
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">

            <div class="bg-blue-600 text-white rounded-xl p-6 shadow">
                <h3 class="text-lg">Total Products</h3>

                <p class="text-4xl font-bold mt-3">
                    {{ stats.total_products }}
                </p>
            </div>

            <div class="bg-green-600 text-white rounded-xl p-6 shadow">
                <h3 class="text-lg">Categories</h3>

                <p class="text-4xl font-bold mt-3">
                    {{ stats.total_categories }}
                </p>
            </div>

            <div class="bg-purple-600 text-white rounded-xl p-6 shadow">
                <h3 class="text-lg">Average Price</h3>

                <p class="text-4xl font-bold mt-3">
                    ${{ stats.average_price }}
                </p>
            </div>

            <div class="bg-red-600 text-white rounded-xl p-6 shadow">
                <h3 class="text-lg">Highest Price</h3>

                <p class="text-4xl font-bold mt-3">
                    ${{ stats.highest_price }}
                </p>
            </div>

            <div class="bg-yellow-500 text-white rounded-xl p-6 shadow">
                <h3 class="text-lg">Lowest Price</h3>

                <p class="text-4xl font-bold mt-3">
                    ${{ stats.lowest_price }}
                </p>
            </div>

        </div>

    </div>
</template>

<script setup>

import {ref,onMounted} from 'vue'
import axios from 'axios'

const stats=ref({
    total_products:0,
    total_categories:0,
    average_price:0,
    highest_price:0,
    lowest_price:0
})

const loadDashboard=async()=>{

    const response=await axios.get('/api/dashboard')

    if(response.data.success){

        stats.value=response.data

    }

}

onMounted(loadDashboard)

</script>