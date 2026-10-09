<template>
  <div class="mb-8 space-y-6">
    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
      <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
        <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
          <i class="fa-solid fa-boxes-stacked text-xl"></i>
        </div>
        <div>
          <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Products</span>
          <h3 class="text-2xl font-bold text-slate-800">{{ stats.total_products || 0 }}</h3>
        </div>
      </div>

      <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
        <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl">
          <i class="fa-solid fa-layer-group text-xl"></i>
        </div>
        <div>
          <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Categories</span>
          <h3 class="text-2xl font-bold text-slate-800">{{ stats.total_categories || 0 }}</h3>
        </div>
      </div>

      <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
        <div class="p-3 bg-purple-100 text-purple-600 rounded-xl">
          <i class="fa-solid fa-copyright text-xl"></i>
        </div>
        <div>
          <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Brands</span>
          <h3 class="text-2xl font-bold text-slate-800">{{ stats.total_brands || 0 }}</h3>
        </div>
      </div>

      <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
        <div class="p-3 bg-amber-100 text-amber-600 rounded-xl">
          <i class="fa-solid fa-[#10B981] fa-dollar-sign text-xl"></i>
        </div>
        <div>
          <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Average Price</span>
          <h3 class="text-2xl font-bold text-slate-800">${{ parseFloat(stats.average_price || 0).toFixed(2) }}</h3>
        </div>
      </div>

      <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
        <div class="p-3 bg-teal-100 text-teal-600 rounded-xl">
          <i class="fa-solid fa-vault text-xl"></i>
        </div>
        <div>
          <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Asset Valuation</span>
          <h3 class="text-2xl font-bold text-slate-800">${{ parseFloat(stats.total_inventory_value || 0).toFixed(2) }}</h3>
        </div>
      </div>
    </div>

    <!-- Chart.js Category Distribution Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-chart-column text-indigo-600"></i> Category Asset Valuation & Stock Volume Chart
          </h2>
          <p class="text-xs text-slate-400 mt-0.5">Real-time inventory valuation and product distribution per category</p>
        </div>
        <button 
          @click="loadDashboard" 
          class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5"
        >
          <i class="fa-solid fa-rotate flex"></i> Refresh Chart
        </button>
      </div>

      <div class="h-64 relative">
        <canvas id="categoryChart"></canvas>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'

const stats = ref({
  total_products: 0,
  in_stock_count: 0,
  total_categories: 0,
  total_brands: 0,
  average_price: 0,
  highest_price: 0,
  lowest_price: 0,
  total_inventory_value: 0,
  categories: []
})

let chartInstance = null

const renderChart = () => {
  const ctx = document.getElementById('categoryChart')
  if (!ctx || !stats.value.categories) return

  if (chartInstance) {
    chartInstance.destroy()
  }

  const labels = stats.value.categories.map(c => c.name)
  const productCounts = stats.value.categories.map(c => c.products_count)
  const assetValues = stats.value.categories.map(c => c.total_asset_value || 0)

  if (window.Chart) {
    chartInstance = new window.Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Product Count',
            data: productCounts,
            backgroundColor: '#6366F1',
            borderRadius: 6,
            yAxisID: 'y'
          },
          {
            label: 'Total Asset Value ($)',
            data: assetValues,
            backgroundColor: '#10B981',
            borderRadius: 6,
            yAxisID: 'y1'
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            type: 'linear',
            display: true,
            position: 'left',
            title: { display: true, text: 'Product Count' }
          },
          y1: {
            type: 'linear',
            display: true,
            position: 'right',
            grid: { drawOnChartArea: false },
            title: { display: true, text: 'Total Asset Value ($)' }
          }
        }
      }
    })
  }
}

const loadDashboard = async () => {
  try {
    const response = await axios.get('/api/dashboard')
    if (response.data.success) {
      stats.value = response.data
      nextTick(() => {
        renderChart()
      })
    }
  } catch (error) {
    console.error('Error loading dashboard stats:', error)
  }
}

onMounted(loadDashboard)
</script>