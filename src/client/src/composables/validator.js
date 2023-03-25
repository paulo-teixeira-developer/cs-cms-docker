export function validate(objValidate, objRules) {

    let validate = { fails: [] };

    Object.entries(objRules).forEach(function (ruleValue, index) {

        const fieldName = ruleValue[0];
        const field = objRules[fieldName];
        const fieldValue = objValidate[fieldName];
        const fieldRule = objRules[fieldName]['rules'];
        const fieldLabel = (field.hasOwnProperty("label") ? field.label : fieldName);

        if (fieldRule.hasOwnProperty("required")) {
            if (fieldValue == null || isEmpty(fieldValue)) {
                validate.fails.push(`o campo ${fieldLabel} está vazio.`);
                return;
            }
        }

        if (fieldRule.hasOwnProperty("email")) {
            if (!isEmail(fieldValue)) {
                validate.fails.push(`o campo ${fieldLabel} deve ser do tipo e-mail.`);
                return;
            }
        }

        if (fieldRule.hasOwnProperty("min")) {
            if (isMin(fieldValue, fieldRule.min)) {
                validate.fails.push(`o campo ${fieldLabel} deve ter no mínimo ${fieldRule.min} caracteres.`);
                return;
            }
        }

        if (fieldRule.hasOwnProperty("max")) {
            if (isMax(fieldValue, fieldRule.max)) {
                validate.fails.push(`o campo ${fieldLabel} deve ter no máximo ${fieldRule.max} caracteres.`);
                return;
            }
        }

        if (fieldRule.hasOwnProperty("equal")) {
            if (!equal(fieldValue, objValidate[fieldRule.equal.field])) {
                validate.fails.push(`o campo ${fieldLabel} e ${fieldRule.equal.label} devem corresponder.`);
                return;
            }
        }
    });

    return validate;
}

export function equal(fieldValue, equalValue) {
    return (fieldValue == equalValue) ? true : false;
}

export function isEmpty(fieldValue) {
    return (fieldValue.length <= 0) ? true : false;
}

export function isEmail(fieldValue) {
    const re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    return re.test(fieldValue);
}

export function isMin(fieldValue, minValue) {
    return (fieldValue.length < minValue) ? true : false;
}

export function isMax(fieldValue, maxValue) {
    return (fieldValue.length > maxValue) ? true : false;
}

export function getCodeError(code) {
    switch (code) {
        case "ERR_NETWORK": return ['Sem conexão com a internet.']
        case "ERR_BAD_REQUEST": return ['Não autorizado.']
        default: return ["Ocorreu um erro"]
    }
}

export function getCatFile(value) {
    const img = ['jpg', 'jpeg', 'png', 'svg']
    const audio = ['mp3', 'wav']

    if (img.includes(value))
        return "image"
    else if (audio.includes(value))
        return "audio"

    return null;
}

export const isImage = (extesion) => ['jpg', 'jpeg', 'png', 'svg'].includes(extesion);
export const isAudio = (extesion) => ['mp3', 'wav'].includes(extesion);


export function bytesToSize(bytes) {
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes === 0) return 'n/a';
    const i = parseInt(Math.floor(Math.log(Math.abs(bytes)) / Math.log(1024)), 10);
    if (i === 0) return `${bytes} ${sizes[i]}`;
    return `${(bytes / (1024 ** i)).toFixed(1)} ${sizes[i]}`;
}




















