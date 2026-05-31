import type { DateValue } from '@internationalized/date';
import { parseDate } from '@internationalized/date';

/**
 * Convert MM-DD-YYYY → DateValue
 */
export function stringToDateValue(date: string | null): DateValue | null {
    if (!date) return null;

    const [year, month, day] = date.split('-');

    return parseDate(`${year}-${month}-${day}`);
}
export function dateValueToString(date: DateValue | null): string | null {
    if (!date) return null;

    const m = String(date.month).padStart(2, '0');
    const d = String(date.day).padStart(2, '0');

    return `${m}-${d}-${date.year}`;
}
