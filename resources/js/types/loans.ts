import type { DateValue } from '@internationalized/date';
export interface Loan {
    id: string | number;
    LoanTypeID: string ;
    EmpNbr: string;
    OrigBal: string | number;
    DedAmt: string | number;
    StartDate:  DateValue | null;
    Frequency: string;
    BalanceAmt: string | number;
    BalanceasofDate: string | number;
}