import {
  Rule, Tree, SchematicsException,
  apply, url, applyTemplates, move,
  chain, mergeWith
} from '@angular-devkit/schematics';

import { strings, normalize } from '@angular-devkit/core';

import { Schema as previewComponent } from '../schema';
import { addComponentToModule } from '../utils/add-component-to-module';

export function previewComponent(options: previewComponent): Rule {
  const path = require('path');
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

    const projectName = options.project as string;

    const project = workspace.projects[projectName];

    const projectType = project.projectType === 'application' ? 'app' : 'lib';

    if (options.path === undefined) {
      options.path = `${project.sourceRoot}/${projectType}/${options.name}/preview-${options.name}`;
    } else {
      options.path = `${project.sourceRoot}/${projectType}/${options.path}/preview-${options.name}`;
    }


    const templateSource = apply(url(path.normalize(__dirname+'/files').replace('C:', '')), [
      applyTemplates({
        classify: strings.classify,
        dasherize: strings.dasherize,
        camelize:strings.camelize,
        name: options.name,
      }),
      move(normalize(options.path as string))
    ]);

    addComponentToModule(tree, strings.classify(`preview-${options.name}-component`), `${options.path}/preview-${strings.dasherize(options.name)}.component`);

    return chain([
      mergeWith(templateSource)
    ]);
  };
}
