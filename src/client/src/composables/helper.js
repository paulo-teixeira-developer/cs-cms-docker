export function abbrName(name) {
    const first_word = name.substr(0, 1);
    const last_word = name.match(/\w+$/)[0].substr(0, 1);
    return `${first_word}${last_word}`
}




















