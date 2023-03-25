<script setup>
import { reactive, ref, watch } from 'vue'
import VInput from '@/components/VInput.vue'

/**extesões tiptap**/
import { FontSize } from '@/composables/tiptap/extension.js'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import Document from '@tiptap/extension-document'
import Paragraph from '@tiptap/extension-paragraph'
import Text from '@tiptap/extension-text'
import History from '@tiptap/extension-history'
import Heading from '@tiptap/extension-heading'
import Bold from '@tiptap/extension-bold'
import FontFamily from '@tiptap/extension-font-family'
import TextStyle from '@tiptap/extension-text-style'
import Italic from '@tiptap/extension-italic'
import Underline from '@tiptap/extension-underline'
import { Color } from '@tiptap/extension-color'
import TextAlign from '@tiptap/extension-text-align'
import OrderedList from '@tiptap/extension-list-item'
import BulletList from '@tiptap/extension-bullet-list'
import Link from '@tiptap/extension-link'
import Image from '@tiptap/extension-image'
import CodeBlock from '@tiptap/extension-code-block'
import HardBreak from '@tiptap/extension-hard-break'

/** props **/
const emit = defineEmits(['update:modelValue'])
const props = defineProps({
    'label': String,
    'modelValue': {
        type: String,
        default: '',
    },
    'fontFamily': {
        type: Object,
        default: []
    },
    'textColor': {
        type: Object,
        default: [
            'black', 'red', 'blue',
        ]
    },
    'btnClass': String,
})

/** props tiptap **/
const showColor = ref(false)
const linkText = reactive({ show: false, link: '' })
const imgContent = reactive({ show: false, url: '' })
const loadData = ref(false)

/** load tiptap **/
const textEditor = useEditor({
    editable: true,
    content: props.modelValue,
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML())
    },
    extensions: [
        Document,
        Link,
        Paragraph,
        Text,
        History,
        Bold,
        Heading,
        TextStyle,
        FontFamily.configure({
            types: ['textStyle'],
        }),
        FontSize,
        Italic,
        Underline,
        Color,
        TextAlign.configure({
            types: ['heading', 'paragraph'],
        }),
        BulletList,
        OrderedList,
        Image,
        CodeBlock,
        HardBreak
    ],
    editorProps: {
        attributes: {
            class: 'containerTextEditor',
        },
    },
})

watch(() => props.modelValue, (newModelValue) => {
    if(!loadData.value){
        textEditor.value?.commands.setContent(newModelValue);
        loadData.value = true;
    }
})

/** funções tiptap **/
function setShowColor() {
    showColor.value = !showColor.value
}

function setColorInText(color) {
    textEditor.value?.commands.setColor(color)
    setShowColor()
}

function setShowLink() {
    linkText.link = ''
    linkText.show = !linkText.show
}

function setLinkText() {
    textEditor.value?.commands.setLink({ href: linkText.link, target: '_blank' })
    setShowLink()
}

function setShowImg() {
    imgContent.url = ''
    imgContent.show = !imgContent.show
}

function setImgUrl() {
    textEditor.value?.commands.setImage({ src: imgContent.url, })
    setShowImg()
}

</script>

<template>
    <div :class="['v-text-editor', btnClass]">
        <label class="mb-1">{{ label }}</label>
        <div class="text-editor">
            <div class="edit-bar">
                <div class="bl-tool br-right">
                    <span class="icons icon-chevron-left" @click="textEditor.chain().undo().run()"></span>
                    <span class="icons icon-chevron-right" @click="textEditor.chain().redo().run()"></span>
                </div>
                <div class="bl-tool br-right">
                    <div class="v-dropdown">
                        <div class="dropdown-btn"><span>Títulos</span></div>
                        <div class="dropdown-content">
                            <span class="dropdown-item" v-for="headingNumber in 6" :value="headingNumber"
                                @click="textEditor.chain().toggleHeading({ level: headingNumber }).run()">H{{ headingNumber }}</span>
                        </div>
                    </div>
                    <div class="v-dropdown">
                        <div class="dropdown-btn"><span>Fonte</span></div>
                        <div class="dropdown-content">
                            <span class="dropdown-item" v-for="font in props.fontFamily" :value="font"
                                @click="textEditor.chain().setFontFamily(font).run()">{{ font }}</span>
                        </div>
                    </div>
                    <div class="v-dropdown">
                        <div class="dropdown-btn"><span>Tamanho</span></div>
                        <div class="dropdown-content">
                            <span class="dropdown-item" @click='textEditor.chain().setFontSize(.8).run()'>Small</span>
                            <span class="dropdown-item" @click='textEditor.chain().unsetFontSize().run()'>Normal</span>
                            <span class="dropdown-item" @click='textEditor.chain().setFontSize(1.5).run()'>Large</span>
                            <span class="dropdown-item" @click='textEditor.chain().setFontSize(2).run()'>Huge</span>
                        </div>
                    </div>
                    <div class="v-dropdown">
                        <div class="dropdown-btn"><span>Formatar</span></div>
                        <div class="dropdown-content">
                            <span class="dropdown-item" @click='textEditor.chain().focus().clearNodes().unsetAllMarks().run()'>Limpar
                                Formatação</span>
                        </div>
                    </div>
                </div>
                <div class="bl-tool br-right">
                    <span class="icons icon-bold" @click="textEditor.chain().toggleBold().run()"></span>
                    <span class="icons icon-italic" @click="textEditor.chain().toggleItalic().run()"></span>
                    <span class="icons icon-underline" @click="textEditor.chain().toggleUnderline().run()"></span>
                    <div class="popup-color">
                        <span class="icons icon-font-color" @click="setShowColor()"></span>
                        <div class="popup-container" v-if="showColor">
                            <div class="dropdown-item" v-for="color in props.textColor" @click="setColorInText(color)"
                                :style="{ background: color }"></div>
                        </div>
                    </div>
                </div>
                <div class="bl-tool br-right">
                    <span class="icons icon-align-left" @click="textEditor.chain().setTextAlign('left').run()"></span>
                    <span class="icons icon-align-middle"
                        @click="textEditor.chain().setTextAlign('center').run()"></span>
                    <span class="icons icon-align-right" @click="textEditor.chain().setTextAlign('right').run()"></span>
                    <span class="icons icon-list-ul" @click="textEditor.chain().toggleBulletList().run()"></span>
                </div>
                <div class="bl-tool">
                    <div class="popup-default">
                        <span class="icons icon-link-alt" @click="setShowLink"></span>
                        <div class="popup-container" v-if="linkText.show">
                            <v-input v-model.trim="linkText.link" label="Url" placeholder="digite a url" />
                            <button @click="setLinkText()">Inserir</button>
                        </div>
                    </div>
                    <div class="popup-default">
                        <span class="icons icon-picture-square" @click="setShowImg"></span>
                        <div class="popup-container" v-if="imgContent.show">
                            <v-input v-model.trim="imgContent.url" label="Url" placeholder="digite a url" />
                            <button @click="setImgUrl()">Inserir</button>
                        </div>
                    </div>
                    <span class="icons icon-code-block" @click="textEditor.chain().toggleCodeBlock().run()"></span>
                </div>
            </div>
            <div class="body-editor">
                <editor-content :editor="textEditor" />
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.v-text-editor {
    width: 100%;
    display: flex;
    flex-direction: column;

    label {
        font-weight: 500;
    }

    .text-editor {
        width: 100%;
        height: auto;
        background: $wth-primary;

        .edit-bar {
            width: 100%;
            height: auto;
            background: $wth-primary;
            border: $br-primary;
            border-radius: $bdra-primary $bdra-primary 0px 0px;
            display: flex;
            align-items: center;
            user-select: none;

            .br-right {
                border-right: $br-primary;
            }

            .bl-tool {
                height: 40px;
                display: flex;
                align-items: center;

                .icons {
                    margin: 0 .5rem;
                    font-size: 1.7rem;
                    color: $blk-secondary;
                    cursor: pointer;
                    transition: 100ms;

                    &:hover {
                        color: $blue-primary;
                    }
                }
            }

            /** dropdown **/
            .v-dropdown {
                position: relative;
                display: inline-block;
                margin: 0 .5rem;
                cursor: pointer;

                .dropdown-btn {
                    height: 30px;
                    background: $wth-primary;
                    border: $br-primary;
                    border-radius: $bdra-primary;
                    padding: 0 10px;
                    user-select: none;
                    display: flex;
                    justify-content: center;
                    align-items: center;

                    span {
                        font-size: .8rem;
                    }
                }

                .dropdown-content {
                    display: none;
                    min-width: 100%;
                    position: absolute;
                    background-color: $wth-primary;
                    box-shadow: $shadow-primary;
                    z-index: 1;

                    .dropdown-item {
                        font-size: .8rem;
                        color: $blk-secondary;
                        padding: 10px;
                        text-decoration: none;
                        display: block;
                        white-space: nowrap;

                        &:hover {
                            background: $grey-primary;
                        }
                    }

                    .is-active {
                        background: $grey-primary;
                    }
                }

                &:hover .dropdown-content {
                    display: block;
                }
            }

            .popup-color {
                position: relative;

                .popup-container {
                    width: auto;
                    height: auto;
                    box-shadow: $shadow-primary;
                    background: #FFFFFF;
                    padding: .8rem;
                    position: absolute;
                    z-index: 1;
                    display: grid;
                    grid-template-columns: auto auto auto;
                    gap: 5px;

                    .dropdown-item {
                        width: 20px;
                        height: 20px;
                        border: 1px solid $grey-primary;
                    }
                }
            }

            .popup-default {
                position: relative;

                .popup-container {
                    min-width: 200px;
                    height: auto;
                    box-shadow: $shadow-primary;
                    background: #FFFFFF;
                    padding: .8rem;
                    position: absolute;
                    z-index: 1;

                    button {
                        text-decoration: none;
                        border: none;
                        background: $blue-primary;
                        color: $wth-primary;
                        padding: 5px;
                    }
                }
            }
        }
    }
}
</style>