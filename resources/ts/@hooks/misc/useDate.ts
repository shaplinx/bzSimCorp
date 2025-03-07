import { format } from 'date-fns';
//I'm using indonesian as locale
import { id } from 'date-fns/locale';

export function quickDateFormat(date : number | string | Date) {
    return format(new Date(date), "eee d MMM yyyy 'pukul' HH.mm", {locale:id})
}

export function dateFormat(date : number | string | Date, formatString : string) {
    return format(new Date(date), formatString, {locale:id})
}


