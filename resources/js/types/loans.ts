use Illuminate\Support\Facades\Route;
export interface Loan {
    id?: string | number;
    public_id: string;
    LoanTypeID: string ;
    EmpNbr: string;
    OrigBal: number;
    DedAmt:  number;
    StartDate:  string ;
    Frequency: string;
    BalanceAmt: number;
    BalanceasofDate: number;
}

export interface LoanError {
    LoanTypeID: string ;
    EmpNbr: string;
    OrigBal: string;
    DedAmt: string;
    StartDate:  string;
    Frequency: string;
    BalanceAmt: string;
    BalanceasofDate: string;
}