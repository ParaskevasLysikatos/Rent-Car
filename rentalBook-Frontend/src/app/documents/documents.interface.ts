export interface IDocuments {
    id: string;
    type_id :string;
    user_id : string;
  user: string;
    path : string;
    mime_type : string;
    md5 : string;
    comments: string;
    name : string;
    document_type : string;
    public_path: string;
    documents:IDocuments
}
