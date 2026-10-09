<template>
  <div class="min-h-screen bg-slate-100 p-4 md:p-8">
    <div class="max-w-7xl mx-auto space-y-8">

      <!-- TOP CONTROL HEADER & EXPORT TOOLBAR -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
          <div class="flex items-center gap-3">
            <div class="p-3 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-xl text-white shadow-md">
              <i class="fa-solid fa-filter text-xl"></i>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Multi-Faceted Product Filter Studio</h1>
              <p class="text-sm text-slate-500 font-medium">Laravel 12 + Vue 3 Dynamic Checkboxes, URL Sync & Multi-Format Exports</p>
            </div>
          </div>
        </div>

        <!-- Multi-Format Export Studio Buttons -->
        <div class="flex flex-wrap items-center gap-3">
          <button 
            @click="exportCSV" 
            class="px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-sm"
          >
            <i class="fa-solid fa-file-csv text-base"></i> Export CSV
          </button>

          <button 
            @click="exportExcel" 
            class="px-4 py-2.5 bg-teal-50 hover:bg-teal-100 text-teal-700 border border-teal-200 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-sm"
          >
            <i class="fa-solid fa-file-excel text-base"></i> Export Excel
          </button>

          <button 
            @click="exportPrintPDF" 
            class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-sm"
          >
            <i class="fa-solid fa-file-pdf text-base"></i> PDF / Print Report
          </button>

          <button 
            @click="copyShareableLink" 
            class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition-all flex items-center gap-2"
          >
            <i class="fa-solid fa-share-nodes text-base"></i> Copy Shareable URL
          </button>
        </div>
      </div>

      <!-- ACTIVE FILTER CHIPS / BADGES BAR -->
      <div v-if="activeFilterChips.length > 0" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5 mr-2">
              <i class="fa-solid fa-tags text-indigo-500"></i> Active Filters ({{ activeFilterChips.length }}):
            </span>

            <span 
              v-for="chip in activeFilterChips" 
              :key="chip.key" 
              class="px-3 py-1.5 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-semibold flex items-center gap-2 shadow-sm hover:bg-indigo-100 transition-all cursor-pointer"
              @click="removeFilterChip(chip)"
            >
              {{ chip.label }}
              <i class="fa-solid fa-xmark text-rose-500 font-bold hover:text-rose-700"></i>
            </span>
          </div>

          <button 
            @click="resetAllFilters" 
            class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
          >
            <i class="fa-solid fa-rotate-left"></i> Reset All Filters
          </button>
        </div>
      </div>

      <!-- MAIN FILTER & RESULTS GRID CONTAINER -->
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- SIDEBAR FILTER MATRIX (LEFT COLUMN) -->
        <div class="space-y-6">
          <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
              <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-blue-600"></i> Filter Matrix
              </h2>
              <span class="text-xs text-slate-400 font-medium">Auto URL Syncing</span>
            </div>

            <!-- SEARCH INPUT -->
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Search Product / Brand</label>
              <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-sm"></i>
                <input 
                  v-model="filters.search" 
                  @input="onSearchInput" 
                  type="text" 
                  placeholder="e.g. Headphones, Apple..."
                  class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all"
                >
                
                <!-- Live Suggestions -->
                <div v-if="suggestions.length > 0" class="absolute left-0 right-0 mt-1 bg-white rounded-xl shadow-xl border border-slate-200 z-50 overflow-hidden divide-y divide-slate-100">
                  <div 
                    v-for="item in suggestions" 
                    :key="item.id" 
                    class="p-3 text-xs font-semibold text-slate-700 hover:bg-blue-50 cursor-pointer flex justify-between items-center"
                    @click="selectSuggestion(item)"
                  >
                    <span>{{ item.name }}</span>
                    <span class="text-[10px] text-slate-400 font-mono">${{ parseFloat(item.price).toFixed(2) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- IN-STOCK ONLY WATCHDOG SWITCH -->
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex justify-between items-center">
              <div>
                <span class="block text-xs font-bold text-slate-800">In-Stock Only Watchdog</span>
                <span class="text-[11px] text-slate-400">Exclude out-of-stock items</span>
              </div>
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" v-model="filters.in_stock" @change="applyFilters" class="sr-only peer">
                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
              </label>
            </div>

            <!-- DUAL-THUMB INTERACTIVE PRICE RANGE SLIDER -->
            <div>
              <div class="flex justify-between items-center mb-2">
                <label class="text-xs font-bold text-slate-600 uppercase">Price Range ($)</label>
                <span class="text-xs font-mono font-bold text-blue-600">
                  ${{ filters.min_price || bounds.min_price }} - ${{ filters.max_price || bounds.max_price }}
                </span>
              </div>

              <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                  <span class="text-[10px] text-slate-400 block mb-1">Min Price ($)</span>
                  <input 
                    v-model.number="filters.min_price" 
                    @change="applyFilters"
                    type="number" 
                    class="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-300 font-mono font-semibold"
                    :placeholder="bounds.min_price"
                  >
                </div>

                <div>
                  <span class="text-[10px] text-slate-400 block mb-1">Max Price ($)</span>
                  <input 
                    v-model.number="filters.max_price" 
                    @change="applyFilters"
                    type="number" 
                    class="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-300 font-mono font-semibold"
                    :placeholder="bounds.max_price"
                  >
                </div>
              </div>
            </div>

            <!-- MULTI-CATEGORY CHECKBOX MATRIX -->
            <div>
              <span class="block text-xs font-bold text-slate-600 uppercase mb-3">Categories (Multi-Select)</span>
              <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                <label 
                  v-for="cat in categories" 
                  :key="cat.id" 
                  class="flex items-center justify-between text-xs font-medium text-slate-700 hover:text-slate-900 cursor-pointer p-1.5 rounded-lg hover:bg-slate-50 transition-all"
                >
                  <div class="flex items-center gap-2">
                    <input 
                      type="checkbox" 
                      :value="cat.id" 
                      v-model="filters.category_ids"
                      @change="applyFilters"
                      class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span>{{ cat.name }}</span>
                  </div>
                  <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">
                    {{ cat.products_count || 0 }}
                  </span>
                </label>
              </div>
            </div>

            <!-- MULTI-BRAND CHECKBOX MATRIX -->
            <div>
              <span class="block text-xs font-bold text-slate-600 uppercase mb-3">Brands (Multi-Select)</span>
              <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                <label 
                  v-for="brand in brandsList" 
                  :key="brand.brand" 
                  class="flex items-center justify-between text-xs font-medium text-slate-700 hover:text-slate-900 cursor-pointer p-1.5 rounded-lg hover:bg-slate-50 transition-all"
                >
                  <div class="flex items-center gap-2">
                    <input 
                      type="checkbox" 
                      :value="brand.brand" 
                      v-model="filters.brands"
                      @change="applyFilters"
                      class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                    >
                    <span>{{ brand.brand }}</span>
                  </div>
                  <span class="text-[10px] font-bold bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full">
                    {{ brand.count || 0 }}
                  </span>
                </label>
              </div>
            </div>

            <!-- RATING FILTER -->
            <div>
              <span class="block text-xs font-bold text-slate-600 uppercase mb-2">Minimum Rating</span>
              <select 
                v-model="filters.min_rating" 
                @change="applyFilters"
                class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-medium bg-white"
              >
                <option value="">All Ratings</option>
                <option value="4.5">⭐ 4.5 Stars & Above</option>
                <option value="4.0">⭐ 4.0 Stars & Above</option>
                <option value="3.5">⭐ 3.5 Stars & Above</option>
              </select>
            </div>

          </div>
        </div>

        <!-- PRODUCT CATALOG GRID (RIGHT 3 COLUMNS) -->
        <div class="lg:col-span-3 space-y-6">
          
          <!-- SORTING & PAGINATION SUMMARY BAR -->
          <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-xs text-slate-500 font-medium">
              Showing <span class="font-bold text-slate-900">{{ products.from || 0 }}</span> - <span class="font-bold text-slate-900">{{ products.to || 0 }}</span> of <span class="font-bold text-slate-900">{{ products.total || 0 }}</span> Products Found
            </div>

            <div class="flex items-center gap-3">
              <span class="text-xs font-bold text-slate-500">Sort By:</span>
              <select 
                v-model="filters.sort_by" 
                @change="applyFilters" 
                class="px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-semibold bg-white"
              >
                <option value="created_at">Newest Arrivals</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
                <option value="rating_desc">Highest Rated</option>
                <option value="name_asc">Name: A to Z</option>
              </select>
            </div>
          </div>

          <!-- NO RESULTS FOUND EMPTY STATE -->
          <div v-if="products.data.length === 0" class="bg-white rounded-2xl shadow-sm border border-slate-200 text-center py-16 p-6">
            <i class="fa-solid fa-filter-circle-xmark text-5xl text-slate-300 mb-4"></i>
            <h3 class="text-lg font-bold text-slate-800">No Products Matched Your Filter Matrix</h3>
            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Try clearing some checkboxes, expanding price range, or clearing the search box.</p>
            <button 
              @click="resetAllFilters" 
              class="mt-6 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-xs shadow-md transition-all"
            >
              Reset All Filters
            </button>
          </div>

          <!-- PRODUCTS GRID CARDS -->
          <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="product in products.data" 
              :key="product.id"
              class="bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-all p-5 flex flex-col justify-between"
            >
              <div>
                <div class="flex justify-between items-start mb-3">
                  <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full text-[11px] font-bold">
                    {{ product.category?.name || 'Category' }}
                  </span>
                  <span 
                    :class="[
                      'px-2 py-0.5 rounded text-[10px] font-bold', 
                      product.in_stock ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'
                    ]"
                  >
                    {{ product.in_stock ? 'In Stock' : 'Out of Stock' }}
                  </span>
                </div>

                <h3 class="font-bold text-slate-900 text-base mb-1 hover:text-blue-600 transition-colors">
                  {{ product.name }}
                </h3>

                <p class="text-xs text-slate-400 mb-4 line-clamp-2">
                  {{ product.description || 'Premium product details...' }}
                </p>
              </div>

              <div>
                <div class="flex justify-between items-center pt-3 border-t border-slate-100">
                  <div>
                    <span class="text-[10px] text-slate-400 font-bold uppercase block">Brand</span>
                    <span class="text-xs font-bold text-indigo-600">{{ product.brand || 'Generic' }}</span>
                  </div>

                  <div class="text-right">
                    <span class="text-[10px] text-slate-400 font-bold uppercase block">Price</span>
                    <span class="text-lg font-extrabold text-slate-900">${{ parseFloat(product.price).toFixed(2) }}</span>
                  </div>
                </div>

                <div class="mt-3 flex justify-between items-center text-xs font-semibold text-amber-500">
                  <span>⭐ {{ product.rating || 4.5 }} / 5.0</span>
                  <span class="text-slate-400 text-[11px]">{{ product.stock_quantity || 10 }} units left</span>
                </div>
              </div>
            </div>
          </div>

          <!-- PAGINATION LINKS -->
          <div v-if="products.links && products.links.length > 3" class="flex justify-center gap-1.5 pt-4">
            <button 
              v-for="link in products.links" 
              :key="link.label"
              :disabled="!link.url"
              @click="changePage(link.url)"
              v-html="link.label"
              :class="[
                'px-3.5 py-2 text-xs font-semibold rounded-xl border transition-all',
                link.active ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50',
                !link.url ? 'opacity-40 cursor-not-allowed' : ''
              ]"
            ></button>
          </div>

        </div>

      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import axios from 'axios'
import { debounce } from 'lodash'

const products = ref({ data: [], links: [], from: 0, to: 0, total: 0 })
const categories = ref([])
const brandsList = ref([])
const suggestions = ref([])
const bounds = ref({ min_price: 0, max_price: 3000 })

const filters = reactive({
  search: '',
  category_ids: [],
  brands: [],
  min_price: '',
  max_price: '',
  in_stock: false,
  min_rating: '',
  sort_by: 'created_at',
  sort_order: 'desc'
})

// Active Filter Chips Computed Property
const activeFilterChips = computed(() => {
  const chips = []

  if (filters.search) {
    chips.push({ key: 'search', label: `🔍 Search: "${filters.search}"` })
  }

  if (filters.category_ids.length > 0) {
    const selectedNames = categories.value
      .filter(c => filters.category_ids.includes(c.id))
      .map(c => c.name)
      .join(', ')
    chips.push({ key: 'category_ids', label: `📂 Categories: ${selectedNames}` })
  }

  if (filters.brands.length > 0) {
    chips.push({ key: 'brands', label: `🏷️ Brands: ${filters.brands.join(', ')}` })
  }

  if (filters.in_stock) {
    chips.push({ key: 'in_stock', label: `✅ In-Stock Only` })
  }

  if (filters.min_price || filters.max_price) {
    chips.push({ 
      key: 'price', 
      label: `💰 Price: $${filters.min_price || 0} - $${filters.max_price || bounds.value.max_price}` 
    })
  }

  if (filters.min_rating) {
    chips.push({ key: 'min_rating', label: `⭐ Rating: ${filters.min_rating}+` })
  }

  return chips
})

// Synchronize URL with active filters
const syncFiltersToUrl = () => {
  const queryParams = new URLSearchParams()

  if (filters.search) queryParams.set('search', filters.search)
  if (filters.category_ids.length > 0) queryParams.set('category_ids', filters.category_ids.join(','))
  if (filters.brands.length > 0) queryParams.set('brands', filters.brands.join(','))
  if (filters.in_stock) queryParams.set('in_stock', '1')
  if (filters.min_price) queryParams.set('min_price', filters.min_price)
  if (filters.max_price) queryParams.set('max_price', filters.max_price)
  if (filters.min_rating) queryParams.set('min_rating', filters.min_rating)
  if (filters.sort_by) queryParams.set('sort_by', filters.sort_by)

  const newUrl = window.location.pathname + (queryParams.toString() ? '?' + queryParams.toString() : '')
  window.history.replaceState(null, '', newUrl)
}

// Load filters from URL on page load
const loadFiltersFromUrl = () => {
  const queryParams = new URLSearchParams(window.location.search)

  if (queryParams.has('search')) filters.search = queryParams.get('search')
  if (queryParams.has('category_ids')) {
    filters.category_ids = queryParams.get('category_ids').split(',').map(Number)
  }
  if (queryParams.has('brands')) {
    filters.brands = queryParams.get('brands').split(',')
  }
  if (queryParams.has('in_stock')) filters.in_stock = queryParams.get('in_stock') === '1'
  if (queryParams.has('min_price')) filters.min_price = queryParams.get('min_price')
  if (queryParams.has('max_price')) filters.max_price = queryParams.get('max_price')
  if (queryParams.has('min_rating')) filters.min_rating = queryParams.get('min_rating')
  if (queryParams.has('sort_by')) filters.sort_by = queryParams.get('sort_by')
}

// Fetch products from API
const fetchProducts = async (page = 1) => {
  try {
    const params = {
      search: filters.search,
      category_ids: filters.category_ids.join(','),
      brands: filters.brands.join(','),
      in_stock: filters.in_stock ? 1 : 0,
      min_price: filters.min_price,
      max_price: filters.max_price,
      min_rating: filters.min_rating,
      sort_by: filters.sort_by,
      page: page
    }

    const response = await axios.get('/api/products', { params })
    if (response.data.success) {
      products.value = response.data.data
      if (response.data.meta) {
        bounds.value.min_price = response.data.meta.min_price_bound
        bounds.value.max_price = response.data.meta.max_price_bound
      }
    }
  } catch (error) {
    console.error('Error fetching products:', error)
  }
}

// Apply Filters Handler
const applyFilters = () => {
  syncFiltersToUrl()
  fetchProducts()
}

// Debounced search input handler
const onSearchInput = debounce(() => {
  applyFilters()
  fetchSuggestions()
}, 300)

// Fetch search suggestions
const fetchSuggestions = async () => {
  if (filters.search.length < 2) {
    suggestions.value = []
    return
  }

  try {
    const response = await axios.get('/api/suggestions', { params: { search: filters.search } })
    if (response.data.success) {
      suggestions.value = response.data.data
    }
  } catch (error) {
    console.error('Error fetching suggestions:', error)
  }
}

const selectSuggestion = (item) => {
  filters.search = item.name
  suggestions.value = []
  applyFilters()
}

// Remove single filter chip
const removeFilterChip = (chip) => {
  if (chip.key === 'search') filters.search = ''
  if (chip.key === 'category_ids') filters.category_ids = []
  if (chip.key === 'brands') filters.brands = []
  if (chip.key === 'in_stock') filters.in_stock = false
  if (chip.key === 'price') { filters.min_price = ''; filters.max_price = '' }
  if (chip.key === 'min_rating') filters.min_rating = ''

  applyFilters()
}

// Reset all filters
const resetAllFilters = () => {
  filters.search = ''
  filters.category_ids = []
  filters.brands = []
  filters.min_price = ''
  filters.max_price = ''
  filters.in_stock = false
  filters.min_rating = ''
  filters.sort_by = 'created_at'

  applyFilters()
}

// Fetch categories and brands for matrix
const loadMetadata = async () => {
  try {
    const [catRes, brandRes] = await Promise.all([
      axios.get('/api/categories'),
      axios.get('/api/brands')
    ])

    if (catRes.data.success) categories.value = catRes.data.data
    if (brandRes.data.success) brandsList.value = brandRes.data.data
  } catch (error) {
    console.error('Error loading metadata:', error)
  }
}

// Change page handler
const changePage = (url) => {
  if (!url) return
  const pageParam = new URL(url).searchParams.get('page')
  fetchProducts(pageParam)
}

// Copy Shareable URL
const copyShareableLink = () => {
  navigator.clipboard.writeText(window.location.href)
  alert('Shareable Filter Link copied to clipboard!')
}

// Multi-Format Export Studio (CSV)
const exportCSV = () => {
  let csvContent = "data:text/csv;charset=utf-8,ID,Name,Brand,Price,Rating,InStock,Category\n"
  products.value.data.forEach(p => {
    csvContent += `"${p.id}","${p.name}","${p.brand}","${p.price}","${p.rating}","${p.in_stock ? 'Yes' : 'No'}","${p.category?.name || ''}"\n`
  })

  const encodedUri = encodeURI(csvContent)
  const link = document.createElement("a")
  link.setAttribute("href", encodedUri)
  link.setAttribute("download", `Filtered_Products_${new Date().toISOString().slice(0, 10)}.csv`)
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
}

// Multi-Format Export Studio (Excel)
const exportExcel = () => {
  let excelContent = "ID\tProduct Name\tBrand\tPrice ($)\tRating\tIn Stock\tCategory\n"
  products.value.data.forEach(p => {
    excelContent += `${p.id}\t${p.name}\t${p.brand}\t${p.price}\t${p.rating}\t${p.in_stock ? 'Yes' : 'No'}\t${p.category?.name || ''}\n`
  })

  const blob = new Blob([excelContent], { type: "application/vnd.ms-excel" })
  const link = document.createElement("a")
  link.href = URL.createObjectURL(blob)
  link.download = `Filtered_Products_${new Date().toISOString().slice(0, 10)}.xls`
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
}

// Multi-Format Export Studio (PDF/Print)
const exportPrintPDF = () => {
  window.print()
}

onMounted(() => {
  loadMetadata()
  loadFiltersFromUrl()
  fetchProducts()
})
</script>