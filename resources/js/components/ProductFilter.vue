<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-6">
        <!-- Header -->
        <div class="max-w-7xl mx-auto mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600">
                            Product Filter System
                        </span>
                    </h1>
                    <p class="mt-2 text-gray-600">Find exactly what you're looking for with powerful filters</p>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="hidden md:flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ products.total || 0 }}</p>
                            <p class="text-xs text-gray-500">Total Products</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto">
            <!-- Filters Card -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-2">
                        <div class="p-2 bg-gradient-to-r from-blue-100 to-purple-100 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Filters</h2>
                    </div>
                    
                    <button
                        @click="resetFilters"
                        class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 rounded-lg hover:from-gray-200 hover:to-gray-300 transition-all duration-200 hover:shadow-md"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Reset All</span>
                    </button>
                </div>

                <!-- Filters Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Search Input -->
                    <div class="group">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400 group-focus-within:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search Products
                        </label>
                        <div class="relative">
                            <input
                                v-model="filters.search"
                                type="text"
                                placeholder="Type to search..."
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all duration-200"
                                @input="debouncedFilter"
                            />
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="group">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400 group-focus-within:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Category
                        </label>
                        <div class="relative">
                            <select
                                v-model="filters.category_id"
                                @change="filterProducts"
                                class="w-full appearance-none pl-10 pr-10 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all duration-200"
                            >
                                <option value="">All Categories</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Min Price -->
                    <div class="group">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400 group-focus-within:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Min Price
                        </label>
                        <div class="relative">
                            <input
                                v-model="filters.min_price"
                                type="number"
                                min="0"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all duration-200"
                                @input="debouncedFilter"
                            />
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <span class="text-gray-400 font-medium">$</span>
                            </div>
                        </div>
                    </div>

                    <!-- Max Price -->
                    <div class="group">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400 group-focus-within:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Max Price
                        </label>
                        <div class="relative">
                            <input
                                v-model="filters.max_price"
                                type="number"
                                min="0"
                                step="0.01"
                                placeholder="1000.00"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all duration-200"
                                @input="debouncedFilter"
                            />
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <span class="text-gray-400 font-medium">$</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sort Options -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl border border-blue-100">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Sort Options</span>
                    </div>
                    
                    <div class="flex flex-wrap gap-3">
                        <select
                            v-model="filters.sort_by"
                            @change="filterProducts"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        >
                            <option value="created_at">Date Added</option>
                            <option value="price">Price</option>
                            <option value="name">Name</option>
                        </select>
                        
                        <select
                            v-model="filters.sort_order"
                            @change="filterProducts"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        >
                            <option value="desc">Descending</option>
                            <option value="asc">Ascending</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Results Summary -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-600">
                            Showing <span class="font-bold text-gray-900">{{ products.from || 0 }}</span> to 
                            <span class="font-bold text-gray-900">{{ products.to || 0 }}</span> of 
                            <span class="font-bold text-gray-900">{{ products.total || 0 }}</span> results
                        </p>
                    </div>
                    <div v-if="Object.values(filters).some(val => val !== '' && val !== null)" class="mt-2">
                        <span class="text-xs text-gray-500">Active filters: {{ activeFilterCount }}</span>
                    </div>
                </div>
                
                <div v-if="loading" class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-blue-100 to-purple-100 rounded-lg">
                    <div class="w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-sm font-medium text-blue-700">Loading products...</span>
                </div>
            </div>

            <!-- Products Grid -->
            <div v-if="!loading && products.data.length === 0" class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-200">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No products found</h3>
                    <p class="text-gray-600 mb-6">Try adjusting your search or filter criteria to find what you're looking for.</p>
                    <button
                        @click="resetFilters"
                        class="inline-flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 hover:shadow-lg"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Reset All Filters</span>
                    </button>
                </div>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="product in products.data" 
                    :key="product.id" 
                    class="group bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-xl hover:border-blue-200 transition-all duration-300 overflow-hidden"
                >
                    <!-- Product Header -->
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span 
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold mb-2"
                                    :class="getCategoryColor(product.category?.name)"
                                >
                                    {{ product.category?.name || 'Uncategorized' }}
                                </span>
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-200 line-clamp-1">
                                    {{ product.name }}
                                </h3>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-900">${{ formatPrice(product.price) }}</div>
                                <div class="text-xs text-gray-500 mt-1">Price</div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            {{ product.description || 'No description available' }}
                        </p>
                        
                        <!-- Additional Info -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center space-x-1 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-xs">{{ formatDate(product.created_at) }}</span>
                            </div>
                            <button 
                                class="flex items-center space-x-1 text-blue-600 hover:text-blue-700 text-sm font-medium group-hover:underline"
                                @click="viewProduct(product)"
                            >
                                <span>View Details</span>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Gradient Bottom Border -->
                    <div class="h-1 bg-gradient-to-r from-transparent via-blue-500 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="products.data.length > 0" class="mt-10">
                <nav class="flex items-center justify-between bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button
                            :disabled="!products.prev_page_url"
                            @click="goToPage(products.prev_page_url)"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                        >
                            Previous
                        </button>
                        <button
                            :disabled="!products.next_page_url"
                            @click="goToPage(products.next_page_url)"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                        >
                            Next
                        </button>
                    </div>
                    
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Page <span class="font-medium">{{ products.current_page || 1 }}</span> of 
                                <span class="font-medium">{{ products.last_page || 1 }}</span>
                            </p>
                        </div>
                        
                        <div>
                            <nav class="relative z-0 inline-flex rounded-lg shadow-sm -space-x-px">
                                <button
                                    v-for="(link, index) in products.links"
                                    :key="index"
                                    :disabled="!link.url || link.active"
                                    @click="goToPage(link.url)"
                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-all duration-200"
                                    :class="[
                                        link.active 
                                            ? 'z-10 bg-gradient-to-r from-blue-600 to-purple-600 border-blue-500 text-white' 
                                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                        index === 0 ? 'rounded-l-lg' : '',
                                        index === products.links.length - 1 ? 'rounded-r-lg' : '',
                                        'disabled:opacity-50 disabled:cursor-not-allowed'
                                    ]"
                                    v-html="link.label"
                                ></button>
                            </nav>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- View Product Modal -->
        <div v-if="selectedProduct" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900">Product Details</h3>
                    <button @click="selectedProduct = null" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Modal Content -->
                <div class="p-6">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <span :class="getCategoryColor(selectedProduct.category?.name)" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold mb-2">
                                {{ selectedProduct.category?.name || 'Uncategorized' }}
                            </span>
                            <h4 class="text-2xl font-bold text-gray-900">{{ selectedProduct.name }}</h4>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-gray-900">${{ formatPrice(selectedProduct.price) }}</div>
                            <div class="text-sm text-gray-500 mt-1">Total Price</div>
                        </div>
                    </div>
                    
                    <div class="prose max-w-none">
                        <h5 class="text-lg font-semibold text-gray-900 mb-2">Description</h5>
                        <p class="text-gray-600 mb-6">{{ selectedProduct.description || 'No description available' }}</p>
                        
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-500">Category</div>
                                <div class="font-medium text-gray-900">{{ selectedProduct.category?.name || 'N/A' }}</div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-500">Created</div>
                                <div class="font-medium text-gray-900">{{ formatDate(selectedProduct.created_at) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button @click="selectedProduct = null" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, reactive, computed } from 'vue';
import axios from 'axios';
import { debounce } from 'lodash';

export default {
    name: 'ProductFilter',
    
    setup() {
        const products = ref({
            data: [],
            links: [],
            current_page: 1,
            from: null,
            to: null,
            total: 0,
            last_page: 1,
        });
        
        const categories = ref([]);
        const loading = ref(false);
        const selectedProduct = ref(null);
        
        const filters = reactive({
            search: '',
            category_id: '',
            min_price: '',
            max_price: '',
            sort_by: 'created_at',
            sort_order: 'desc'
        });

        // Computed property for active filter count
        const activeFilterCount = computed(() => {
            return Object.values(filters).filter(
                val => val !== '' && val !== null && val !== undefined && 
                      (typeof val !== 'string' || val.trim() !== '')
            ).length - 2; // Subtract sort_by and sort_order from count
        });

        // Helper function to format price safely
        const formatPrice = (price) => {
            if (!price && price !== 0) return '0.00';
            
            const numPrice = typeof price === 'string' ? parseFloat(price) : price;
            
            if (isNaN(numPrice)) return '0.00';
            
            return numPrice.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        };

        // Helper function to format date
        const formatDate = (dateString) => {
            if (!dateString) return 'N/A';
            
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        };

        // Helper function to get category color
        const getCategoryColor = (categoryName) => {
            const colors = {
                'Electronics': 'bg-blue-100 text-blue-800',
                'Books': 'bg-green-100 text-green-800',
                'Clothing': 'bg-purple-100 text-purple-800',
                'Home & Garden': 'bg-yellow-100 text-yellow-800',
                'Sports': 'bg-red-100 text-red-800',
                'Toys': 'bg-pink-100 text-pink-800',
                'Beauty': 'bg-indigo-100 text-indigo-800',
                'Automotive': 'bg-gray-100 text-gray-800',
                'Food': 'bg-orange-100 text-orange-800',
                'Health': 'bg-teal-100 text-teal-800'
            };
            
            return colors[categoryName] || 'bg-gray-100 text-gray-800';
        };

        // Truncate description
        const truncateDescription = (description) => {
            if (!description) return '';
            return description.length > 100 ? description.substring(0, 100) + '...' : description;
        };

        // Fetch products
        const fetchProducts = async (url = null) => {
            loading.value = true;
            
            try {
                const apiUrl = url || '/api/products';
                const params = url ? {} : { ...filters };
                
                // Clean up empty values
                Object.keys(params).forEach(key => {
                    if (params[key] === '' || params[key] === null || params[key] === undefined) {
                        delete params[key];
                    }
                });
                
                const response = await axios.get(apiUrl, { params });
                
                if (response.data.success) {
                    products.value = response.data.data;
                }
            } catch (error) {
                console.error('Error fetching products:', error);
                products.value = {
                    data: [],
                    links: [],
                    current_page: 1,
                    from: null,
                    to: null,
                    total: 0,
                    last_page: 1,
                };
            } finally {
                loading.value = false;
            }
        };

        // Fetch categories
        const fetchCategories = async () => {
            try {
                const response = await axios.get('/api/categories');
                
                if (response.data.success) {
                    categories.value = response.data.data;
                } else {
                    categories.value = [];
                }
            } catch (error) {
                console.error('Error fetching categories:', error);
                categories.value = [];
            }
        };

        // Filter products
        const filterProducts = () => {
            fetchProducts();
        };

        // Debounced filter
        const debouncedFilter = debounce(() => {
            fetchProducts();
        }, 500);

        // Go to page
        const goToPage = (url) => {
            if (url) {
                fetchProducts(url);
            }
        };

        // Reset filters
        const resetFilters = () => {
            filters.search = '';
            filters.category_id = '';
            filters.min_price = '';
            filters.max_price = '';
            filters.sort_by = 'created_at';
            filters.sort_order = 'desc';
            
            fetchProducts();
        };

        // View product details
        const viewProduct = (product) => {
            selectedProduct.value = product;
        };

        onMounted(() => {
            fetchProducts();
            fetchCategories();
        });

        return {
            products,
            categories,
            loading,
            filters,
            selectedProduct,
            activeFilterCount,
            fetchProducts,
            filterProducts,
            debouncedFilter,
            goToPage,
            resetFilters,
            viewProduct,
            formatPrice,
            formatDate,
            getCategoryColor,
            truncateDescription
        };
    }
}
</script>

<style scoped>
/* Smooth transitions */
* {
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Custom scrollbar for modal */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Line clamp utility */
.line-clamp-1 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
}

.line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
}

/* Gradient animations */
@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

.bg-gradient-animate {
    background-size: 200% 200%;
    animation: gradient 3s ease infinite;
}

/* Hover effects */
.group:hover .group-hover\:translate-x-1 {
    transform: translateX(0.25rem);
}

/* Focus styles */
:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
}

/* Smooth shadow transitions */
.shadow-sm {
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Custom animations */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>