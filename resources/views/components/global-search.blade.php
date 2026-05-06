<div class="ts-search-container" 
     x-data="globalSearch()" 
     @click.outside="closeSearch"
     style="position: relative; flex: 1; max-width: 320px; margin: 0 16px;">
     
    <div style="position: relative; display: flex; align-items: center;">
        <svg width="16" height="16" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24" style="position: absolute; left: 12px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" 
               class="ts-search-input" 
               placeholder="Search ThreadSpace..." 
               x-model="query" 
               @input="onInput"
               @focus="isOpen = true"
               style="width: 100%; background: #0d1117; border: 1px solid #30363d; border-radius: 999px; padding: 6px 16px 6px 36px; font-size: .85rem; color: #f0f6fc; outline: none; transition: border-color .2s;">
        
        <button x-show="query.length > 0" @click="clearSearch" style="position: absolute; right: 12px; background: none; border: none; color: #8b949e; cursor: pointer; padding: 0;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <!-- Dropdown -->
    <div x-show="isOpen && query.length > 0" 
         style="display: none; position: absolute; top: calc(100% + 8px); left: 0; right: 0; background: #161b22; border: 1px solid #30363d; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,.5); overflow: hidden; z-index: 1000;"
         x-transition>
        
        <div x-show="loading" style="padding: 16px; text-align: center; color: #8b949e; font-size: .85rem;">
            Searching...
        </div>

        <div x-show="!loading && hasResults()">
            <!-- Posts -->
            <template x-if="results.posts.length > 0">
                <div style="padding: 8px 0; border-bottom: 1px solid #21262d;">
                    <div style="padding: 4px 14px; font-size: .75rem; font-weight: 700; color: #8b949e; text-transform: uppercase; letter-spacing: .5px;">Posts</div>
                    <template x-for="post in results.posts" :key="post.id">
                        <a :href="post.url" class="ts-search-item">
                            <i class="fa-regular fa-comment" style="color:#ffa657;width:16px;text-align:center;"></i>
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-size: .85rem; font-weight: 600; color: #d4d9e0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" x-text="post.title"></div>
                                <div style="font-size: .75rem; color: #6b7280;" x-text="post.intent"></div>
                            </div>
                        </a>
                    </template>
                </div>
            </template>

            <!-- Communities -->
            <template x-if="results.communities.length > 0">
                <div style="padding: 8px 0; border-bottom: 1px solid #21262d;">
                    <div style="padding: 4px 14px; font-size: .75rem; font-weight: 700; color: #8b949e; text-transform: uppercase; letter-spacing: .5px;">Communities</div>
                    <template x-for="comm in results.communities" :key="comm.id">
                        <a :href="comm.url" class="ts-search-item">
                            <i class="fa-solid fa-people-group" style="color:#a371f7;width:16px;text-align:center;"></i>
                            <div style="font-size: .85rem; font-weight: 600; color: #d4d9e0;" x-text="comm.name"></div>
                        </a>
                    </template>
                </div>
            </template>

            <!-- Users -->
            <template x-if="results.users.length > 0">
                <div style="padding: 8px 0;">
                    <div style="padding: 4px 14px; font-size: .75rem; font-weight: 700; color: #8b949e; text-transform: uppercase; letter-spacing: .5px;">Users</div>
                    <template x-for="user in results.users" :key="user.id">
                        <a :href="user.url" class="ts-search-item">
                            <div style="width: 20px; height: 20px; border-radius: 50%; background: linear-gradient(135deg,#1a8cd8,#0e9a74); display: flex; align-items: center; justify-content: center; font-size: .6rem; font-weight: 800; color: #fff;">
                                <span x-text="user.name.charAt(2).toUpperCase()"></span>
                            </div>
                            <div style="font-size: .85rem; font-weight: 600; color: #d4d9e0;" x-text="user.name"></div>
                        </a>
                    </template>
                </div>
            </template>
        </div>

        <div x-show="!loading && !hasResults() && query.length > 0" style="padding: 16px; text-align: center; color: #8b949e; font-size: .85rem;">
            No results found for "<span x-text="query"></span>"
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('globalSearch', () => ({
        isOpen: false,
        query: '',
        loading: false,
        debounceTimeout: null,
        results: {
            posts: [],
            communities: [],
            users: []
        },

        onInput() {
            this.isOpen = true;
            if (this.query.trim().length === 0) {
                this.clearResults();
                return;
            }

            this.loading = true;
            clearTimeout(this.debounceTimeout);
            
            this.debounceTimeout = setTimeout(() => {
                this.fetchResults();
            }, 300);
        },

        async fetchResults() {
            try {
                const response = await fetch('/search?q=' + encodeURIComponent(this.query), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();
                this.results = data;
            } catch (error) {
                console.error("Search error:", error);
            } finally {
                this.loading = false;
            }
        },

        hasResults() {
            return this.results.posts.length > 0 || 
                   this.results.communities.length > 0 || 
                   this.results.users.length > 0;
        },

        clearResults() {
            this.results = { posts: [], communities: [], users: [] };
            this.loading = false;
        },

        clearSearch() {
            this.query = '';
            this.isOpen = false;
            this.clearResults();
        },

        closeSearch() {
            this.isOpen = false;
        }
    }));
});
</script>
