import type { DateValue } from '@internationalized/date';
export interface Employee {
    id?: string;
    public_id: string;
    EmpNbr: string;
    FirstName: string;
    LastName: string;
    MidName: string;
    Suffix: string;
    Address: string;
    CityProv: string;
    Position: string;
    Assignment: string;
    SalaryGrade: string;
    BirthDate: string;
    EmployDate: string ;
    RegularDate: string ;
    ResignDate: string ;
    BasicPay:  number;
    DailyRate:  number;
    HourlyRate:  number;
    Status: boolean;
    SSSNbr: string;
    PHICNbr: string;
    HDMFNbr: string;
    TIN: string;
    Degree: string;
    AllowReg: string | number;
    BPIATM: string;
    BPIEmpCode: string;
    PIN: string;
    PERAAID: string;
    Group: string;
}
export interface EmployeeError {
    EmpNbr: string;
    FirstName: string;
    LastName: string;
    MidName: string;
    Suffix: string;
    Address: string;
    CityProv: string;
    Position: string;
    Assignment: string;
    SalaryGrade: string;
    BirthDate: string
    EmployDate: string
    RegularDate: string
    ResignDate: string
    BasicPay: string;
    DailyRate: string;
    HourlyRate: string;
    Status: string;
    SSSNbr: string;
    PHICNbr: string;
    HDMFNbr: string;
    TIN: string;
    Degree: string;
    AllowReg: string;
    BPIATM: string;
    BPIEmpCode: string;
    PIN: string;
    PERAAID: string;
    Group: string;
}
