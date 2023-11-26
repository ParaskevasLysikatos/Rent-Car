import {
  Rule, Tree, SchematicsException,
  chain
} from '@angular-devkit/schematics';

import { Schema as wholeComponent } from '../schema';
import { createComponent } from '../create-component';
import { editComponent } from '../edit-component';
import { multiFormComponent } from '../multi-form-component';
import { previewComponent } from '../preview-component';
import { service } from '../service';
import { resInterface } from '../interface';

export function wholeComponent(options: wholeComponent): Rule {
  return (tree: Tree) => {
    const workspaceConfig = tree.read('/angular.json');
    if (!workspaceConfig) {
      throw new SchematicsException('Could not find Angular workspace configuration');
    }

    // convert workspace to string
    const workspaceContent = workspaceConfig.toString();

    // parse workspace string into JSON object
    const workspace = JSON.parse(workspaceContent);

    if (!options.project) {
      options.project = workspace.defaultProject;
    }

    return chain([
      createComponent({...options}),
      editComponent({...options}),
      multiFormComponent({...options}),
      previewComponent({...options}),
      service({...options}),
      resInterface({...options}),
    ]);
  };
}
