import { SchematicsException, Tree } from "@angular-devkit/schematics";
import { addDeclarationToModule } from '../utils/ast-utils';
import * as ts from 'typescript';
import { InsertChange } from '../utils/change';
import { strings } from "@angular-devkit/core";

export function addComponentToModule(tree: Tree, componentName: string, componentPath: string): void {
    const workspaceConfig = tree.read('/angular.json');
    if (!workspaceConfig) {
        throw new SchematicsException('Could not find Angular workspace configuration');
    }

    // convert workspace to string
    const workspaceContent = workspaceConfig.toString();

    // parse workspace string into JSON object
    const workspace = JSON.parse(workspaceContent);
    const projectName = workspace.defaultProject;
    const project = workspace.projects[projectName];
    const projectType = project.projectType === 'application' ? 'app' : 'lib';
    const text = tree.read(`${project.sourceRoot}/${projectType}/app.module.ts`)?.toString() ?? '';
    const source = ts.createSourceFile(`${project.sourceRoot}/${projectType}/app.module.ts`, text, ts.ScriptTarget.Latest, true);
    const declarationChanges = addDeclarationToModule(source, `${project.sourceRoot}/${projectType}/app.module.ts`,
    strings.classify(componentName), componentPath);
    const declarationRecorder = tree.beginUpdate(`${project.sourceRoot}/${projectType}/app.module.ts`);
    for (const change of declarationChanges) {
        if (change instanceof InsertChange) {
            declarationRecorder.insertLeft(change.pos, change.toAdd);
        }
    }
    tree.commitUpdate(declarationRecorder);
}