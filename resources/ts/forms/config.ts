import {
    createInput,
    type DefaultConfigOptions,
    bindings
} from "@formkit/vue";
import { createLibraryPlugin, text, form,color ,radio, number,month,week, date, datetimeLocal, file, group, hidden, password, range, search, select, tel, textarea, time, url,checkbox, email } from '@formkit/inputs'
import { createAutoAnimatePlugin } from "@formkit/addons";
import vSelect from "./plugins/vueselect/VueSelect.vue";
import VButtonVue from "./plugins/vbutton/VButton.vue";
import RadioButton from "./plugins/radiobutton/RadioButton.vue";
import VueDatepickerVue from "./plugins/vue-datepicker/VueDatepicker.vue";
import { generateClasses } from "@formkit/themes"
import { repeater } from "./plugins/vRepeater";
import AutoSuggest from "./plugins/autosuggest/AutoSuggest.vue";
import Toggle from "./plugins/toggle/Toggle.vue";
import theme from "./theme"


const defaultInputsLib = createLibraryPlugin({ text,radio,color, form,month,week, number, date, datetimeLocal, file, group, hidden, password, range, search, select, tel, textarea,checkbox, time, url, email, })

const addOnsInputLib = createLibraryPlugin({
    vRepeater: repeater,
    vSelect: createInput(vSelect),
    autoSuggest: createInput(AutoSuggest, { props: ["onSearch"] }),
    button: {
        type: "input",
        component: VButtonVue,
    },
    submit: {
        type: "input",
        component: VButtonVue,
    },
    toggle: createInput(Toggle),
    datepicker: createInput(VueDatepickerVue),
    radioButton:createInput(RadioButton)
})

const formkitConfig = {
    plugins: [defaultInputsLib, createAutoAnimatePlugin(), bindings, addOnsInputLib],
    config: {
        classes: generateClasses(theme),
    },
} satisfies DefaultConfigOptions;

export default formkitConfig;
