<template>
  <div>

    <div v-for="(item,index) in menu_items_sorted"
         :id="'menu-item-' + item.id"
         class="menu-item m-2 px-4 py-1 flex flex-row border rounded-md gap-4 justify-between"
         :class="{
           'bg-blue-100 border-blue-300': item.type == 'product' && item.visible,
           'bg-slate-100 border-slate-300': item.type == 'product' && !item.visible,
           'bg-blue-700 border-blue-800 text-white mt-6': item.type == 'section'
         }"
         :draggable="isDraggable"
         @dragstart="dragStart($event, item)"
         @dragover.prevent="dragOver($event)"
         @drop="onDrop($event)"
    >

      <div class="flex flex-row gap-3">

        <div class="text-blue-500 hover:text-blue-800 cursor-grab">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
        </div>

        <div v-if="item.type == 'product'" class="h-16 w-16 rounded-md overflow-hidden">
          <img v-if="item.image && item.image.src_thumb" :src="item.image.src_thumb"/>
        </div>

        <div class="flex flex-col gap-1">

          <div class="text-lg"
               :class="{
              'text-slate-800': item.type == 'product',
              'text-white': item.type == 'section'
            }"
          >{{ item.title }}</div>


          <div class="text-slate-400 text-sm flex flex-row gap-2"
               :class="{
              'text-slate-400': item.type == 'product',
              'text-slate-200': item.type == 'section'
            }"
          >
            <div v-for="p_item in item.prices" class="flex flex-row gap-1">
              <span>{{ p_item.name }}</span>
              <span>{{ p_item.value }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="flex flex-row gap-2">
        <div @click="addItemBelow(item)" class="cursor-pointer text-blue-500 hover:text-blue-700">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
        </div>
        <div @click="editItem(item)" class="cursor-pointer text-blue-500 hover:text-blue-700">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
          </svg>
        </div>
        <div @click="removeItem(item)" class="cursor-pointer text-blue-500 hover:text-red-500">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
          </svg>
        </div>
      </div>

    </div>


    <!-- Add items -->
    <div v-if="post_created()" class="flex flex-row gap-4 mt-6 px-2">
      <button class="ml-auto bg-blue-100 px-4 py-1 rounded-md text-slate-600 border border-blue-400 hover:bg-blue-300" @click.prevent="createProduct">{{ __('New Menu Item') }}</button>
      <button class="bg-blue-600 px-4 py-1 rounded-md text-white border border-blue-800 hover:bg-blue-800" @click.prevent="createSection">{{ __('New Section') }}</button>
    </div>

    <div v-else>
      {{ __('Please, SAVE THIS POST to begin adding Menu Items.') }}
    </div>



    <Teleport to="#modal"
        transition="fade-transition"
        v-if="createModalOpen"
    >
      <Modal>
        <Card class="bg-white p-0 w-[90%] md:min-w-[640px] md:w-[640px] max-w-full">
          <CreateUpdateMenuItem
            :menu-item-to-edit="menuItemToEdit"
            :menu-item-type-to-create="menuItemTypeToCreate"
            @cancel="createModalOpen = false"
            @save="addingItem"
          />
        </Card>
      </Modal>
    </Teleport>

    <Teleport to="#modal"
              transition="fade-transition"
              v-if="confirmModalOpen"
    >
      <Modal>
        <Card class="bg-white p-0 w-[90%] md:min-w-[640px] md:w-[640px] max-w-full">
          <AreYourSure @cancel="cancelDeleting" @confirm="confirmDeleting">

            <div class="py-8 text-slate-700 text-lg flex items-center justify-center">{{ __('Are your sure to delete this item?') }}</div>

          </AreYourSure>
        </Card>
      </Modal>
    </Teleport>


  </div>
</template>

<script>
import Modal from "./components/Modal.vue"
import Card from "./components/Card.vue"
import CreateUpdateMenuItem from "./components/CreateUpdateMenuItem.vue"
import AreYourSure from "./components/AreYourSure.vue";

export default {
  components: {
    AreYourSure,
    Modal, Card, CreateUpdateMenuItem,
  },

  data() {
    return {
      id: null,
      menu_items: [],
      menuItemToEdit: null,
      addBelowItem: null,
      menuItemTypeToCreate: 'product',
      createModalOpen: false,

      isDraggable: true,
      draggingItem: null,

      confirmModalOpen: false,
      itemToDeDeleted: null
    }
  },

  async created(){
    this.id = window.erm_vars.menu_id
    this.loadMenuItems()
  },

  computed: {
    menu_items_sorted(){
      return _.sortBy(this.menu_items, 'ordering')
    }
  },

  methods: {
    __(label){ // @todo translations
      return label
    },

    // @todo: Only when post has been saved I have a reference to post->ID
    // and can start adding items
    post_created() {
      return window.erm_vars.menu_id
    },

    loadMenuItems() {
      if (window.erm_vars.menu_items.length == 0) {
        return
      }

      for (let i = 0; i < window.erm_vars.menu_items.length; i++){
        let item = window.erm_vars.menu_items[i]

        this.menu_items.push({
          ordering: i,
          id: item.id,
          type: item.type,
          visible: item.visible,
          image: {
            image_id: item.image_id > 0 ? item.image_id : null,
            src_thumb: item.src_thumb,
            src_big: item.src_big,
            image_title: item.image_title,
            image_desc: item.image_desc
          },
          title: item.title,
          description: item.content,
          prices: item.prices
        })
      }

    },

    createProduct() {
      this.menuItemToEdit = null
      this.addBelowItem = null
      this.menuItemTypeToCreate = 'product'
      this.createModalOpen = true
    },

    createSection() {
      this.menuItemToEdit = null
      this.menuItemTypeToCreate = 'section'
      this.createModalOpen = true
    },

    addItemBelow(item) {
      this.menuItemToEdit = null
      this.addBelowItem = item
      this.menuItemTypeToCreate = 'product'
      this.createModalOpen = true
    },

    editItem(item){
      this.menuItemToEdit = item
      this.createModalOpen = true
    },

    removeItem(item){
      this.itemToDeDeleted = item
      this.confirmModalOpen = true
    },

    cancelDeleting(){
      this.confirmModalOpen = false
    },

    confirmDeleting(){
      let item = this.itemToDeDeleted
      this.confirmModalOpen = false

      this.menu_items.splice(this.menu_items.indexOf(item), 1)
      this.save()

      // If it is a section I have to remove it from the DB,
      // otherwise leave it for reuse it
      if (item.type == 'section') {
        let data = {
          action: 'erm_delete_menu_item',
          post_id: item.id
        }
        jQuery.post(ajaxurl, data, response => {})
      }
    },

    toggleVisible(item) {
      item.visible = ! item.visible
    },

    // Find the last ordering and add one
    nextIndexOrdering() {
      let ordering = 1
      let last = _.last(this.menu_items_sorted)
      if (last) {
        ordering = last.ordering + 1
      }
      return ordering
    },

    addingItem(item) {

      // New item added
      if (this.menuItemToEdit === null) {

        if (this.addBelowItem){
          item.ordering = this.addBelowItem.ordering + 0.1
        }
        else {
          item.ordering = this.nextIndexOrdering()
        }

        this.menu_items.push(item)
        this.reorderMenuItems()
      }

      // Updated item
      else {
        this.menuItemToEdit.visible = item.visible
        this.menuItemToEdit.title = item.title
        this.menuItemToEdit.description = item.description
        this.menuItemToEdit.image = item.image
        this.menuItemToEdit.prices = item.prices
      }

      this.createModalOpen = false

      this.save()
    },

    menuItemsId() {
      let ids = []
      for (let i = 0; i < this.menu_items.length; i++){
        ids.push(this.menu_items[i].id)
      }
      return ids
    },

    save() {
      var self = this
      var data = {
        action: 'erm_update_list_menu_items',
        post_id: self.id,
        ids: this.menuItemsId().join(',')
      }
      jQuery.post(ajaxurl, data, () => {})
    },

    // DRAGGING
    //-------------------------------------

    dragStart(evt, item) {
      //console.log(evt.target)
      this.draggingItem = item
    },

    dragOver(evt) {
      let onItemId = evt.target.closest('.menu-item').getAttribute('id').replace('menu-item-','')
      this.removeDraggingClassAllItems()

      if (this.draggingItem.id == onItemId) return
      evt.target.closest('.menu-item').classList.add('dragging-over')
    },

    onDrop(evt) {
      let onItemId = evt.target.closest('.menu-item').getAttribute('id').replace('menu-item-','')
      this.removeDraggingClassAllItems()

      if (this.draggingItem.id == onItemId) return

      // Put the new group just below the onDrop group
      for (let i = 0; i < this.menu_items.length; i++){
        if (this.menu_items[i].id == onItemId) {
          this.draggingItem.ordering = this.menu_items[i].ordering + 0.5
        }
      }

      // Redo the ordering and save
      this.reorderMenuItems()
      this.save()
    },

    reorderMenuItems(){
      let items = _.clone(this.menu_items_sorted)
      for (let i = 0; i < items.length; i++){
        items[i].ordering = i+1
      }
      this.menu_items = items
    },

    removeDraggingClassAllItems() {
      let items = document.getElementsByClassName('menu-item')
      for (let i = 0; i < items.length; i++) {
        items[i].classList.remove('dragging-over')
      }
    },

  },
}
</script>

<style scoped>
.menu-item {
  transition: border-bottom-width 0.25s;
}
.dragging-over {
  transition: border-bottom-width 0.25s;
  border-bottom: 80px solid #7c98ef !important;
}
</style>
