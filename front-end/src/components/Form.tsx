import type React from "react";
import { motion } from "motion/react";
import Input from "./Input";
import { useState } from "react";

export type InputDefinition = {
    name: string,
    label: string,
    type: string,
    hidden?: boolean,
    options?: Array<{
        name: string,
        value?: string
    }>,
    rules: Array<{
        regex: RegExp,
        message: string
    }>
};

type FormParams = {
    title: string,
    description?: string | React.ReactNode,
    inputs: Array<InputDefinition | false>,
    className?: string,
    callBack: CallableFunction,
    children?: React.ReactNode
};

export default function Form({title, description, inputs, className, callBack, children}: FormParams) {
    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        const hasErrors = Object.values(formState).some(
            (field: any) => field.errors.length > 0 || field.value.trim() === ""
        );
        if(hasErrors) {
            return;
        } else {
            return callBack(formState);
        }
    }

    const [formState, setFormState] = useState(() => {
        const initial: Record<string, { value: string; errors: string[] }> = {};
        for (const input of inputs) {
            if(input) initial[input.name] = { value: "", errors: [] };
        }
        return initial;
    });

    const hasErrors = Object.values(formState).some(
        (field: any) => field.errors.length > 0 || field.value.trim() === ""
    );

    const hasVisibleFields = Object.values(inputs).some(
        (field: any) => !field.hidden
    );
    
    return (
        <form action="" className={`form ${className}`} onSubmit={handleSubmit} encType="multipart/form-data">
            <h2 className="subtitle">{title}</h2>
            {description && <p className="paragraph">{description}</p>}
            {inputs.map((i: InputDefinition | false, index: number) => {
                if(!i) return <></>;
                return (
                    <Input 
                        key={`input-${index}`} 
                        type={i.type} 
                        name={i.name} 
                        label={i.label} 
                        rules={i.rules}
                        hidden={i.hidden ?? false}
                        options={i.options ?? undefined}
                        formState={formState}
                        setFormState={setFormState}
                    />
                );
            })}
            <div className="bubble-group">
                {children}
                {
                    hasVisibleFields && <motion.input 
                        whileHover={{scale: 1.05, transition: {duration: 0.15}}}
                        whileTap={{scale: 0.95, transition: {duration: 0.05}}}
                        type="submit" 
                        className="btn btn-primary" 
                        value="Valider" 
                        disabled={hasErrors}
                    />
                }
            </div>
        </form>
    );
}