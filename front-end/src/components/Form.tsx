import type React from "react";
import { motion } from "motion/react";
import Input from "./Input";
import { useState } from "react";

export type InputDefinition = {
    name: string,
    label: string,
    type: string,
    rules: Array<{
        regex: RegExp,
        message: string
    }>
};

type FormParams = {
    title: string,
    description?: string | React.ReactNode,
    inputs: InputDefinition[],
    className?: string,
    callBack: CallableFunction
};

export default function Form({title, description, inputs, className, callBack}: FormParams) {
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
            initial[input.name] = { value: "", errors: [] };
        }
        return initial;
    });

    const hasErrors = Object.values(formState).some(
        (field: any) => field.errors.length > 0 || field.value.trim() === ""
    );
    
    return (
        <form action="" className={`form ${className}`} onSubmit={handleSubmit} encType="multipart/form-data">
            <h2 className="subtitle">{title}</h2>
            <p className="paragraph">{description}</p>
            {inputs.map((i: InputDefinition, index: number) => {
                return (
                    <Input 
                        key={`input-${index}`} 
                        type={i.type} 
                        name={i.name} 
                        label={i.label} 
                        rules={i.rules}
                        formState={formState}
                        setFormState={setFormState}
                    />
                );
            })}
            <motion.input 
                whileHover={{scale: 1.05, transition: {duration: 0.15}}}
                whileTap={{scale: 0.95, transition: {duration: 0.05}}}
                type="submit" 
                className="btn btn-primary" 
                value="Valider" 
                disabled={hasErrors}
            />
        </form>
    );
}