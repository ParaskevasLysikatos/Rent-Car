export class IContact {
    id!: string;
    firstname!: string;
    lastname!: string;
    get full_name() {return this.firstname + ' ' + this.lastname};
    email!: string;
    phone!: string;
    address!: string;
    zip!: string;
    city!: string;
    country!: string;
    birthday!: string;
    identification_number!: string;
    identification_country!: string;
    identification_created!: string;
    identification_expire!: string;
    afm!: string;
    mobile!: string;
    birth_place!:string;
  //how_old: string;
}

