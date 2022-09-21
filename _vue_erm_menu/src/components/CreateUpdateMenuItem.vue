<template>
  <div class="p-0">

    <div class="p-2 bg-slate-100 flex flex-row items-center justify-between"
      :class="{
        'bg-blue-200 text-slate-800': type == 'product',
        'bg-blue-600 text-white': type == 'section'
      }"

    >
      <div v-show="type == 'product'">{{ __('Menu item') }}</div>
      <div v-show="type == 'section'">{{ __('Section') }}</div>
      <div class="font-bold cursor-pointer px-4"
           :class="{
            'text-slate-800': type == 'product',
            'text-white': type == 'section'
      }"
           @click="cancel">x</div>
    </div>

    <div class="p-4 flex flex-col gap-4 w-full">

      <!-- IMAGE -->
      <div v-if="type == 'product'">
        <div v-if="image && image.image_id" class="w-full rounded-md overflow-hidden relative shadow">
          <img :src="image.src_big"/>
          <span @click.prevent="image = null" class="cursor-pointer inline-block w-6 h-6 absolute top-0 right-0 bg-black text-white text-center">x</span>
        </div>
        <div v-else class="h-32 w-full p-4 border border-slate-600 border-dashed flex items-center justify-center rounded-md">
          <div class="text-sm bg-blue-200 p-2 rounded-md text-blue-800 hover:bg-blue-300 cursor-pointer" @click="addImage">{{ __('select image') }}</div>
        </div>
      </div>

      <!-- TITLE, CONTENT, PRICE -->
      <div class="flex flex-col gap-4">

        <!-- visible -->
        <div v-if="type == 'product'" class="flex flex-row gap-2 items-center">
          <div @click="visible = !visible" class="cursor-pointer text-blue-500 hover:text-blue-700">
            <svg v-show="visible" class="w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <svg v-show="!visible" class="w-6 h-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
            </svg>
          </div>

          <label v-if="visible">{{ __('This item will be displayed at the front-end.') }}</label>
          <label v-if="!visible">{{ __('This item will not be displayed at the front-end.') }}</label>
        </div>

        <!-- title -->
        <div class="flex flex-col gap-2">
          <label>{{ __('Title') }}</label>
          <input v-model="title" type="text" class="p-2 border-slate-300 border rounded md"/>
        </div>

        <!-- description -->
        <div class="flex flex-col gap-2">
          <label>{{ __('Description') }}</label>
          <!-- textarea v-model="description" type="text" class="p-2 border-slate-400 border rounded md" rows="5"></textarea -->
          <div class="border-slate-200 border">
            <TinyMceEditor :content="description" ref="tinymce"/>
          </div>
        </div>
      </div>

      <!-- pricing -->
      <div v-if="type == 'product'" class="flex flex-col gap-2">
        <label>{{ __('Prices') }}</label>

        <div v-for="(price,index) in prices" :key="index">
          <div class="flex flex-row items-center gap-2">
            <input v-model="price.name" type="text" class="p-2 border-slate-300 border rounded md w-20" :placeholder="__('Title')"/>
            <input v-model="price.value" type="text" class="p-2 border-slate-300 border rounded md w-20" :placeholder="__('Price')"/>
            <div class="text-slate-700 cursor-pointer" @click.prevent="removePrice(price)">x</div>
          </div>
        </div>

        <div class="flex">
          <div class="cursor-pointer p-2 border border-blue-400 rounded-md bg-blue-200" @click.prevent="addPrice">{{ __('Add price') }}</div>
        </div>
      </div>

    </div>

    <!-- BUTTONS -->
    <div class="border border-t border-slate-200 p-4 flex items-center gap-2">
      <div class="cursor-pointer ml-auto px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-400" @click.prevent="cancel">{{ __('Cancel') }}</div>
      <div class="cursor-pointer px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-400" @click.prevent="save">{{ __('Save') }}</div>
    </div>


  </div>
</template>

<script>
import TinyMceEditor from "./TinyMceEditor.vue";
export default {
  components: {TinyMceEditor},
  emits: ['cancel', 'save'],

  props: {
    menuItemToEdit: {
      default: null,
      required: false
    },
    menuItemTypeToCreate: {
      default: 'product'
    }
  },

  data(){
    return {
      id: null,
      type: 'product',
      visible: true,
      image: null,
      title: '',
      description: '',
      prices: [],
      newItemHasBeenCreated: false,
    }
  },

  created(){
    if (this.menuItemToEdit !== null) {
      this.id = this.menuItemToEdit.id
      this.type = this.menuItemToEdit.type
      this.visible = this.menuItemToEdit.visible
      this.image = this.menuItemToEdit.image
      this.title = this.menuItemToEdit.title
      this.description = this.menuItemToEdit.description
      this.prices = this.menuItemToEdit.prices
    } else {
      this.ajaxCreateNewItem()
    }
  },

  methods: {
    __(label) { // @todo
      return label
    },

    cancel(){
      var self = this

      // I need to remove the item created first
      if (this.newItemHasBeenCreated){
        let data = {
          action: 'erm_delete_menu_item',
          post_id: this.id
        }
        jQuery.post(ajaxurl, data, response => {
          self.$emit('cancel')
        })
      }
      else {
        this.$emit('cancel')
      }
    },

    addImage(){
      var self = this

      //if ( undefined != window.file_frame ) {
      //  window.file_frame.open();
      //  return;
      //}

      window.file_frame = wp.media.frames.file_frame = wp.media({
        frame: 'post',
        state: 'insert',
        //title: 'Insert image',
        //button: { text: 'Select image' },
        multiple: false
      })

      window.file_frame.on('insert', function(){
        var selection = window.file_frame.state().get('selection');
        selection.each( function( attachment, index ){

          var attachment = attachment.toJSON();
          var url = attachment.url;
          var src_thumb = url.replace(/\.([a-z]{3,4})$/,'-150x150.$1');

          self.image = {
            image_id: attachment.id,
            src_thumb: src_thumb,
            src_big: attachment.url,
            image_title: attachment.caption,
            image_desc: attachment.description
          }


        })
      })

      file_frame.open()
    },

    addPrice(){
      this.prices.push({
        name: '',
        value: ''
      })
    },

    removePrice(price){
      this.prices.splice( this.prices.indexOf(price), 1)
    },

    save(){
      var self = this

      let toSave = {
        id: this.id,
        type: this.type,
        visible: this.visible,
        image: this.image,
        title: this.title,
        //description: this.description,
        description: this.$refs.tinymce.getContent(),
        prices: this.prices
      }

      this.ajaxUpdateItem(() => {
        self.$emit('save', toSave)
      })
    },

    ajaxCreateNewItem(){
      var self = this
      let data = {
        action: 'erm_create_menu_item',
        type: this.menuItemTypeToCreate
      }
      jQuery.post(ajaxurl, data, response => {
        if (response.success) {
          let item = response.data

          self.id = item.id
          self.type = item.type
          self.visible = item.visible
          self.image = null
          self.title = item.title
          self.description = item.content
          self.prices = item.prices

          self.newItemHasBeenCreated = true
        }
      })
    },

    ajaxUpdateItem(callback) {
      var data = {
        action: 'erm_update_menu_item',
        post_id: this.id,
        title: this.title,
        content: this.$refs.tinymce.getContent(),
        image_id: this.image ? this.image.image_id : null,
        visible: this.visible,
        prices: this.prices
      }

      jQuery.post(ajaxurl, data, response => {
        if (response.success) {
          callback()
        }
      })
    },

  }//methods
}
</script>
