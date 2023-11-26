import { ICategories } from "../categories/categories.interface";
import { ICharacteristics } from "../characteristics/characteristics.interface";
import { IDocuments } from "../documents/documents.interface";
import { IImage } from "../image-upload/image.interface";
import { IOptions } from "../options/options.interface";
import { IProfiles } from "../_interfaces/profiles.interface";


export interface ITypes {
    id: string;
    slug: string;
    icon: string;
    category_id: string
    min_category: string;
    max_category: string;
    excess: string;
    category: ICategories;
    characteristics: ICharacteristics[];
    characteristicsCount: string;
    options:IOptions[];
    optionsCount:string;
    international_code: string;
    profiles: IProfiles;
  images: IImage;
  imagesCount: string;
}
