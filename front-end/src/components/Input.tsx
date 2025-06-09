import { motion } from "motion/react";
import { useState } from "react";

export default function Input({type, name, label, rules, formState, setFormState}: {type: string, name: string, label: string, rules: Array<{regex: RegExp, message: string}>, formState: any, setFormState: CallableFunction}) {
    function handleChange(name: string, value: string) {
        let errors = [];
        for(let rule of rules) {
            if(!rule.regex.test(value.trim())) {
                errors.push(rule.message);
            };
        }
        setErrors(errors);
        setFormState({
            ...formState,
            [name]: {value, errors}
        });
    }

    const [errors, setErrors] = useState([] as string[]);
    const data = formState[name] as {
        value: string,
        errors: string[]
    };

    return (
        <motion.div
            className={`input ${errors.length > 0 ? "error": ''}`}
            whileHover={{scale: 1.05, transition: {duration: 0.15}}}
        >
            <label htmlFor={`${name}-input`}>{label}</label>
            <input onChange={(e) => handleChange(name, e.target.value)} type={type} id={`${name}-input`} name={name} value={data.value} />
            <span className="small">{errors.length > 0 && errors[0]}</span>
        </motion.div>
    );
}